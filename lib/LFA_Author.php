<?php

class LFA_Author extends WP_User {

	private $avatar = FALSE;

	public function getUrl() {

		return get_author_posts_url($this->ID, $this->user_nicename);
	}

	public function getFullName() {

		return $this->display_name;
	}

	public function getFirstName() {

		return $this->user_firstname;
	}

	public function getID() {

		return $this->ID;
	}

	/*
	 * Ak user nie je blogger, tak to vrati true, pretoze u inych roli nas to nezaujima
	 */
	public function isEnabled() {

		if (!$this->isBlogger()) {
			return TRUE;
		}

		return !!get_metadata("user", $this->ID, "_user_enabled", TRUE);
	}

	public function doEnable() {

		if (!$this->isBlogger()) {
			return FALSE;
		} else if ($this->isEnabled()) {
			return TRUE;
		}

		update_user_meta($this->ID, '_user_enabled', 1);

		lfa_send_mail_blogger_account_activated($this);

		return TRUE;
	}

	public function isBlogger() {

		return in_array(LFA_USER_ROLE_BLOGGER, $this->roles, TRUE);
	}

	public function getPosition() {

		return trim((string)get_metadata("user", $this->ID, "_user_position", TRUE));
	}

	public function getBio() {

		return trim((string)get_the_author_meta("description", $this->ID));
	}

	public function hasAvatar() {

		return $this->getAvatar() !== NULL;
	}

	public function getAvatarSrc() {

		$doc = new DOMDocument();
		$doc->loadHTML($this->getAvatar());
		$imageTags = $doc->getElementsByTagName('img');

		if ($imageTags) {
			foreach ($imageTags as $tag) {
				return $tag->getAttribute('src');
			}
		}

		return NULL;
	}

	public function getAvatar() {

		if ($this->avatar === FALSE) {
			$this->avatar = NULL;

			global $simple_local_avatars;
			$avatar = $simple_local_avatars->get_avatar("", $this->ID, 200);

			if (mb_strlen($avatar)) {
				$this->avatar = $avatar;
			}
		}

		return $this->avatar;
	}
}