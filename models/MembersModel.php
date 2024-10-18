<?php
$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    header("Location: / ");
    exit;
}
if (file_exists("load_classes.php")) {
    require_once "load_classes.php";
} else {
    if (file_exists("../load_classes.php")) {
        require_once "../load_classes.php";
    }
}
class MembersModel extends Model
{
    private $table = "ntk_members";
    public function addNewMember($data)
    {
        return $this->insertData($this->table, $data);
    }
    public function userInfoByUsername($username)
    {
        return $this->getSingle($this->table, "username", $username);
    }
    public function userIPInfo($ip)
    {
        return $this->getSingle($this->table, "registration_ip", $ip);
    }
    public function userInfoByEmail($email)
    {
        return $this->getSingle($this->table, "email", $email);
    }
    public function updateMemberDetails($data, $username)
    {
        return $this->updateData($this->table, "username", $username, $data);
    }
    public function totalAffiliateBalance()
    {
        $query = "SELECT SUM(balance) AS total_affiliate_balance FROM " . $this->table . " WHERE balance > 0";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->execute();
        return $handler->fetch(PDO::FETCH_ASSOC);
    }
    public function addBalance($username, $amount)
    {
        $amount = $this->filter($amount);
        $query = "UPDATE " . $this->table . " SET balance = balance + " . $amount . " WHERE username = ?";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->filter($username));
        $handler->execute();
    }
    public function addEmailCredits($username, $amount)
    {
        $amount = $this->filter($amount);
        $query = "UPDATE " . $this->table . " SET credits = credits + " . $amount . " WHERE username = ?";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->filter($username));
        $handler->execute();
    }
    public function deductBalance($username, $amount)
    {
        $amount = $this->filter($amount);
        $query = "UPDATE " . $this->table . " SET balance = balance - " . $amount . " WHERE username = ?";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->filter($username));
        $handler->execute();
    }
    public function totalMembers()
    {
        return $this->countAll($this->table);
    }
    public function membersList($limit, $offset)
    {
        $membershipController = new MembershipsController();
        $membershipTable = $membershipController->getTable();
        $limit = $this->filter($limit);
        $offset = $this->filter($offset);
        $query = "SELECT " . $this->table . ".*, " . $membershipTable . ".membership_title FROM " . $this->table . ", \n        " . $membershipTable . " WHERE " . $membershipTable . ".id = " . $this->table . ".membership ORDER BY " . $this->table . ".id LIMIT " . $limit . " OFFSET " . $offset;
        $handler = $this->getDBConnection()->prepare($query);
        $handler->execute();
        return $handler->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getMemberDetails($username)
    {
        $membershipController = new MembershipsController();
        $membershipTable = $membershipController->getTable();
        $query = "SELECT " . $this->table . ".*, " . $membershipTable . ".membership_title, " . $membershipTable . ".sales_commission, \n        " . $membershipTable . ".clicks_commission, " . $membershipTable . ".timer_seconds, " . $membershipTable . ".email_sending_limit, " . $membershipTable . ".credits_per_click, ". $membershipTable . ".credits_per_login, " . $membershipTable . ".max_recipient, " . $membershipTable . ".chat_gpt_access, " . $membershipTable . ".chat_gpt_prompt_limit \n         FROM " . $this->table . ", \n        " . $membershipTable . " WHERE " . $membershipTable . ".id = " . $this->table . ".membership AND " . $this->table . ".username = ?";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->filter($username));
        $handler->execute();
        return $handler->fetch(PDO::FETCH_ASSOC);
    }
    public function getMemberDetailsByEmail($email)
    {
        $membershipController = new MembershipsController();
        $membershipTable = $membershipController->getTable();
        $query = "SELECT " . $this->table . ".*, " . $membershipTable . ".membership_title, " . $membershipTable . ".sales_commission, \n        " . $membershipTable . ".clicks_commission, " . $membershipTable . ".timer_seconds, " . $membershipTable . ".email_sending_limit, " . $membershipTable . ".credits_per_click, \n        " . $membershipTable . ".max_recipient, " . $membershipTable . ".chat_gpt_access, " . $membershipTable . ".chat_gpt_prompt_limit \n         FROM " . $this->table . ", \n        " . $membershipTable . " WHERE " . $membershipTable . ".id = " . $this->table . ".membership AND " . $this->table . ".email = ?";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->filter($email));
        $handler->execute();
        return $handler->fetch(PDO::FETCH_ASSOC);
    }
    public function getMemberListByStatus($status, $limit, $offset)
    {
        $membershipController = new MembershipsController();
        $membershipTable = $membershipController->getTable();
        $limit = $this->filter($limit);
        $offset = $this->filter($offset);
        $query = "SELECT " . $this->table . ".*, " . $membershipTable . ".membership_title FROM " . $this->table . ", \n        " . $membershipTable . " WHERE " . $membershipTable . ".id = " . $this->table . ".membership AND account_status = ? ORDER BY " . $this->table . ".id LIMIT " . $limit . " OFFSET " . $offset;
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->filter($status));
        $handler->execute();
        return $handler->fetchAll(PDO::FETCH_ASSOC);
    }
    public function totalMemberByStatus($status)
    {
        return $this->countWithCondition($this->table, "account_status", $status);
    }
    public function updateMemberAccount($data, $username)
    {
        return $this->updateData($this->table, "username", $username, $data);
    }
    public function totalMemberByMembership($membership)
    {
        return $this->countWithCondition($this->table, "membership", $membership);
    }
    public function memberCountries()
    {
        $query = "SELECT country, COUNT(*) AS total_members FROM " . $this->table . " WHERE country != '' GROUP BY country ORDER BY total_members DESC";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->execute();
        return $handler->fetchAll(PDO::FETCH_ASSOC);
    }
    public function searchMemberByUsername($username)
    {
        $username = $this->filter($username);
        $membershipController = new MembershipsController();
        $membershipTable = $membershipController->getTable();
        $query = "SELECT " . $this->table . ".*, " . $membershipTable . ".membership_title FROM " . $this->table . ", " . $membershipTable . "\n        WHERE " . $this->table . ".username LIKE '" . $username . "%' AND " . $this->table . ".membership = " . $membershipTable . ".id ORDER BY username ASC LIMIT 40";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->execute();
        return $handler->fetchAll(PDO::FETCH_ASSOC);
    }
    public function searchMemberByEmail($email)
    {
        $email = $this->filter($email);
        $membershipController = new MembershipsController();
        $membershipTable = $membershipController->getTable();
        $query = "SELECT " . $this->table . ".*, " . $membershipTable . ".membership_title FROM " . $this->table . ", " . $membershipTable . "\n        WHERE " . $this->table . ".email LIKE '" . $email . "%' AND " . $this->table . ".membership = " . $membershipTable . ".id ORDER BY email ASC LIMIT 40";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->execute();
        return $handler->fetchAll(PDO::FETCH_ASSOC);
    }
    public function recentMembers($limit)
    {
        $limit = $this->filter($limit);
        return $this->getAll($this->table, $limit, 0, "DESC");
    }
    public function newMemberToday()
    {
        $todayStart = strtotime(strval(date("Y-m-d")) . "00:00:00");
        $todayEnd = strtotime(strval(date("Y-m-d")) . "23:59:59");
        $query = "SELECT COUNT(*) FROM " . $this->table . " WHERE join_timestamp BETWEEN " . $todayStart . " AND " . $todayEnd . " ORDER BY id DESC LIMIT 5";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->execute();
        return $handler->fetchColumn();
    }
    public function totalReferrals($username)
    {
        return $this->countWithCondition($this->table, "referrer", $username);
    }
    public function deductMemberTextAdCredits($username, $amount)
    {
        $query = "UPDATE " . $this->table . " SET text_ad_credits = text_ad_credits - " . $amount . " WHERE username = ?";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->filter($username));
        return $handler->execute();
    }
    public function deductMemberBannerAdCredits($username, $amount)
    {
        $query = "UPDATE " . $this->table . " SET banner_credits = banner_credits - " . $amount . " WHERE username = ?";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->filter($username));
        return $handler->execute();
    }
    public function deductMemberLoginAdCredits($username, $amount)
    {
        $query = "UPDATE " . $this->table . " SET login_ad_credits = login_ad_credits - " . $amount . " WHERE username = ?";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->filter($username));
        return $handler->execute();
    }
    public function deductMbmberBalance($username, $amount)
    {
        $query = "UPDATE " . $this->table . " SET balance = balance - " . $amount . " WHERE username = ?";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->filter($username));
        return $handler->execute();
    }
    public function deductMbmberCredits($username, $amount)
    {
        $query = "UPDATE " . $this->table . " SET credits = credits - " . $amount . " WHERE username = ?";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->filter($username));
        return $handler->execute();
    }
    public function increaseMemberTextAdCredits($username, $amount)
    {
        $query = "UPDATE " . $this->table . " SET text_ad_credits = text_ad_credits + " . $amount . " WHERE username = ?";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->filter($username));
        return $handler->execute();
    }
    public function increaseMemberBannerCredits($username, $amount)
    {
        $query = "UPDATE " . $this->table . " SET banner_credits = banner_credits + " . $amount . " WHERE username = ?";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->filter($username));
        return $handler->execute();
    }
    public function increaseMemberLoginCredits($username, $amount)
    {
        $query = "UPDATE " . $this->table . " SET login_ad_credits = login_ad_credits + " . $amount . " WHERE username = ?";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->filter($username));
        return $handler->execute();
    }
    public function memberReferrals($limit, $offset, $username)
    {
        $membershipController = new MembershipsController();
        $membershipTable = $membershipController->getTable();
        $limit = $this->filter($limit);
        $offset = $this->filter($offset);
        $query = "SELECT " . $this->table . ".*, " . $membershipTable . ".membership_title FROM " . $this->table . ", \n        " . $membershipTable . " WHERE " . $membershipTable . ".id = " . $this->table . ".membership AND " . $this->table . ".referrer = ? ORDER BY " . $this->table . ".id DESC LIMIT " . $limit . " OFFSET " . $offset;
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->filter($username));
        $handler->execute();
        return $handler->fetchAll(PDO::FETCH_ASSOC);
    }
    public function searchMemberReferral($username, $referralUsername)
    {
        $membershipController = new MembershipsController();
        $membershipTable = $membershipController->getTable();
        $query = "SELECT " . $this->table . ".*, " . $membershipTable . ".membership_title FROM " . $this->table . ", \n        " . $membershipTable . " WHERE " . $membershipTable . ".id = " . $this->table . ".membership AND \n        " . $this->table . ".referrer = ? AND " . $this->table . ".username = ? ORDER BY " . $this->table . ".id DESC LIMIT 1";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->filter($username));
        $handler->bindValue(2, $this->filter($referralUsername));
        $handler->execute();
        return $handler->fetch(PDO::FETCH_ASSOC);
    }
    public function increaseReferralLinkClick($username)
    {
        $query = "UPDATE " . $this->table . " SET referral_link_clicks = referral_link_clicks + 1 WHERE username = ?";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->filter($username));
        return $handler->execute();
    }
    public function increaseEmailClick($username)
    {
        $query = "UPDATE " . $this->table . " SET total_clicks = total_clicks + 1 WHERE username = ?";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->filter($username));
        return $handler->execute();
    }
    public function referralContestLeaderboard($startDate, $endDate)
    {
        $startDate = strtotime($startDate . " 00:00:00");
        $endDate = strtotime($endDate . " 23:59:59");
        $query = "SELECT COUNT(*) as total_referrals, referrer FROM " . $this->table . " WHERE referrer != '' \n        AND join_timestamp BETWEEN " . $startDate . " AND " . $endDate . " AND account_status = 1 GROUP BY referrer ORDER BY total_referrals DESC LIMIT 20";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->execute();
        return $handler->fetchAll(PDO::FETCH_ASSOC);
    }
    public function topReferrersThisMonth()
    {
        $startDate = "01-" . date("m-Y") . " 00:00:00";
        $endDate = date("j-m-Y") . " 23:59:59";
        $startTimeStamp = strtotime($startDate);
        $endTimeStamp = strtotime($endDate);
        $query = "SELECT *, COUNT(id) AS total_referrals FROM " . $this->table . " WHERE referrer != '' AND join_timestamp BETWEEN " . $startTimeStamp . " AND " . $endTimeStamp . " \n        AND account_status = 1 GROUP BY referrer ORDER BY COUNT(id) DESC LIMIT 10";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->execute();
        return $handler->fetchAll(PDO::FETCH_ASSOC);
    }
    public function membershipChange($username, $bonus_credits, $bonus_text_ad, $bonus_banner_ad, $membership_id, $membership_end)
    {
        $query = "UPDATE " . $this->table . " SET credits = credits + " . $bonus_credits . ", banner_credits = banner_credits + " . $bonus_banner_ad . ", \n        text_ad_credits = text_ad_credits + " . $bonus_text_ad . ", membership = ?, membership_end_time = ? WHERE username = ?";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->filter($membership_id));
        $handler->bindValue(2, $this->filter($membership_end));
        $handler->bindValue(3, $this->filter($username));
        return $handler->execute();
    }
    public function getAllMembers()
    {
        $query = "SELECT * FROM " . $this->table . " WHERE account_status = 1";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->execute();
        return $handler->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getAutoEmails()
    {
        $currentTime = time();
        $query = "SELECT * FROM " . $this->table . " WHERE account_status = 1 AND credits > 1 AND membership > 1 AND auto_email_status = 1 \n        AND auto_email_subject != '' AND auto_email_body != '' AND auto_email_website != '' AND membership_end_time == 'Lifetime' OR membership_end_time > " . $currentTime;
        $handler = $this->getDBConnection()->prepare($query);
        $handler->execute();
        return $handler->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getMembersByMembership($membershipId)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE membership = ? AND account_status = 1";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->filter($membershipId));
        $handler->execute();
        return $handler->fetchAll(PDO::FETCH_ASSOC);
    }
    public function membershipEndList()
    {
        $startTime = strtotime(date("d-m-Y") . " 00:00:00");
        $endTime = strtotime(date("d-m-Y") . " 23:59:59");
        $query = "SELECT * FROM " . $this->table . " WHERE membership != 1 AND membership_end_time != 'lifetime' AND \n        membership_end_time BETWEEN " . $startTime . " AND " . $endTime;
        $handler = $this->getDBConnection()->prepare($query);
        $handler->execute();
        return $handler->fetchAll(PDO::FETCH_ASSOC);
    }
    public function vacationEndList()
    {
        $startTime = strtotime(date("d-m-Y") . " 00:00:00");
        $endTime = strtotime(date("d-m-Y") . " 23:59:59");
        $query = "SELECT * FROM " . $this->table . " WHERE account_status = 4 AND \n        vacation_end_time BETWEEN " . $startTime . " AND " . $endTime;
        $handler = $this->getDBConnection()->prepare($query);
        $handler->execute();
        return $handler->fetchAll(PDO::FETCH_ASSOC);
    }
    public function deleteMember($username)
    {
        $this->deleteData($this->table, $username, "username");
    }
}

?>