<?php


/**
 * Class Blog_Article
 * kazdy post ma meta_key="_reviewed_revisions"
 * posledny zaznam v poli sa berie ako ten, podla coho sa sudi, ze ci je revizia approvnuta, rejectnuta alebo pending
 * potom ma este post meta_key="_once_approved", aby sme cez wp query vedeli vybrat posty, ktore uz boli revizovane aspon raz
 */

class Blog_Article {

    const REVISION_STATE_PENDING = "pending";
    const REVISION_STATE_APPROVED = "approved";
    const REVISION_STATE_REJECTED = "rejected";

    /**
     * @var null|WP_Post
     */
    public $post = null;

    public function __construct(WP_Post $post) {
        $this->post = $post;
    }

    public function lastRevisionIsRejected() {
        $revisions = $this->getReviewedRevisions();

        if(empty($revisions)) {
            return false;
        }

        $revision = end($revisions);

        return $revision["status"] === self::REVISION_STATE_REJECTED;
    }

    public function lastRevisionIsPendingReview() {
        $revisions = $this->getReviewedRevisions();

        if(empty($revisions)) {
            return false;
        }

        $revision = end($revisions);

        return $revision["status"] === self::REVISION_STATE_PENDING;
    }

    public function lastRevisionIsApproved() {
        $revisions = $this->getReviewedRevisions();

        if(empty($revisions)) {
            return false;
        }

        $revision = end($revisions);

        return $revision["status"] === self::REVISION_STATE_APPROVED;
    }

    /**
     * @return null|WP_Post
     */
    public function getLastPostRevision() {
        $revisions = wp_get_post_revisions($this->post->ID);

        if(!$revisions) {
            return null;
        }

        return reset($revisions);
    }

    public function pushLastRevisionForReview() {
        return $this->pushRevisionReviewResult(self::REVISION_STATE_PENDING);
    }

    public function pushLastRevisionForRejection($reason) {
        return $this->pushRevisionReviewResult(self::REVISION_STATE_REJECTED, array(
            "reason" => $reason
        ));
    }

    public function pushLastRevisionAsApproved() {
        return $this->pushRevisionReviewResult(self::REVISION_STATE_APPROVED);
    }

    public function modifyPostByRevision(WP_Post $currentPost) {

        $lastRevisionId = $this->getLastApprovedRevision();

        if(!$lastRevisionId) {
            return false;
        }

        $lastRevision = get_post($lastRevisionId);

        if(!($lastRevision instanceof WP_Post)) {
            return false;
        }

        $keys = array(
            "post_date",
            "post_date_gmt",
            "post_content",
            "post_title",
            "post_excerpt",
            "post_modified",
            "post_modified_gmt",
            "post_content_filtered",
            "post_parent",
            "guid",
            "menu_order",
        );

        foreach($keys as $key) {
            $currentPost->$key = $lastRevision->$key;
        }

        return true;
    }

    public function getLastReviewedRevision() {

        $revisionToDisplay = $this->getLastApprovedRevision();

        if(!$revisionToDisplay) {
            return null;
        }

        $post = get_post($revisionToDisplay);

        if(!($post instanceof WP_Post)) {
            return null;
        }

        return $post;
    }

    public function getReviewedRevisions() {
        $revisions = get_post_meta($this->post->ID, "_reviewed_revisions", true);

        if(!is_array($revisions)) {
            $revisions = array();
        }

        return $revisions;
    }

    private function pushRevisionReviewResult($status, array $additionalData = null) {

        $postRevision = $this->getLastPostRevision();

        if(!$postRevision) {
            return false;
        }

        $record = array(
            "id" => $postRevision->ID,
            "status" => $status,
            "date" => date("r"),
            "user_id" => get_current_user_id()
        );

        if(is_array($additionalData)) {
            $record = array_merge($record, $additionalData);
        }

        $revisions = $this->getReviewedRevisions();
        $revisions[] = $record;

        update_post_meta($this->post->ID, "_reviewed_revisions", $revisions);

        if($status === self::REVISION_STATE_APPROVED) {
            if(!get_post_meta($this->post->ID, "_once_approved", true)) {
                update_post_meta($this->post->ID, "_once_approved", 1);
            }
        }

        return true;
    }

    public function getLastApprovedRevision() {
        $revisions = $this->getReviewedRevisions();
        $revisions = array_reverse($revisions);

        if(!empty($revisions)) {
            foreach($revisions as $revision) {
                if($revision["status"] === self::REVISION_STATE_APPROVED) {
                    return (int)$revision["id"];
                }
            }
        }

        return null;
    }
}