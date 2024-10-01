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
class ChatGPTController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new ChatGPTModel();
    }
    public function getSettings()
    {
        return $this->model->getSettings();
    }
    public function totalUserPrompt($username)
    {
        return $this->model->totalUserPrompt($username);
    }
    public function userPromptDetails($id, $username)
    {
        $details = $this->model->getPromptDetails($id);
        if (empty($details)) {
            return "";
        }
        if ($details["username"] != $username) {
            return "";
        }
        return $details;
    }
    public function userPromptList($username)
    {
        $offset = 0;
        if (isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"])) {
            $total = $this->totalUserPrompt($username);
            $total_offset = ceil($total / 30);
            if ($_GET["page"] - 1 < 0) {
                $offset = 0;
            } else {
                if ($total_offset < $_GET["page"] - 1) {
                    $offset = 0;
                } else {
                    $offset = ($_GET["page"] - 1) * 30;
                }
            }
        }
        return $this->model->userPromotList(30, $offset, $username);
    }
    public function userPromptPagination($username)
    {
        $this->pagination(30, $this->totalUserPrompt($username), "chat-gpt-prompt-history.php");
    }
    public function updateSettings()
    {
        if (isset($_POST["api_key"]) && isset($_POST["max_tokens"]) && isset($_POST["admin_csrf_token"]) && isset($_POST["open_ai_model"]) && isset($_POST["chatGPTStatus"])) {
            if (empty($_POST["api_key"]) || empty($_POST["max_tokens"]) || empty($_POST["admin_csrf_token"]) || empty($_POST["open_ai_model"]) || empty($_POST["chatGPTStatus"])) {
                return ["success" => false, "message" => "All fields are required"];
            }
            if (!is_numeric($_POST["max_tokens"])) {
                return ["success" => false, "message" => "Max tokens should be numeric"];
            }
            if (!is_numeric($_POST["chatGPTStatus"]) || $_POST["chatGPTStatus"] != 1 && $_POST["chatGPTStatus"] != 2) {
                return ["success" => false, "message" => "Invalid status"];
            }
            if (!in_array($_POST["open_ai_model"], $this->openAIModels())) {
                return ["success" => false, "message" => "Invalid OpenAI Model"];
            }
            if ($_POST["max_tokens"] < 800) {
                return ["success" => false, "message" => "Please use max tokens more than or equal to 800."];
            }
            $adminController = new AdminController();
            if ($adminController->getAdminCSRFToken() != $_POST["admin_csrf_token"]) {
                return ["success" => false, "message" => "Invalid request"];
            }
            if (!$this->checkOpenAiApiKey($_POST["api_key"]) && $_POST["chatGPTStatus"] == 1) {
                return ["success" => false, "message" => "Invalid API key."];
            }
            $this->model->updateSettings(["api_key" => $_POST["api_key"], "max_tokens" => $_POST["max_tokens"], "open_ai_model" => $_POST["open_ai_model"], "chatGPTStatus" => $_POST["chatGPTStatus"]]);
            return ["success" => true, "message" => "ChatGPT Settings has been updated."];
        }
    }
    public function checkOpenAiApiKey($apiKey)
    {
        $url = "https://api.openai.com/v1/engines";
        $headers = ["Authorization: Bearer " . $apiKey];
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $httpcode == 200;
    }
    public function promptWebsiteTypes()
    {
        return ["Viral Mailer", "E-Commerce", "Website Design Services", "Website Development Services", "Educational Platform", "Real Estate", "Technology & Gadgets", "Web Hosting Service"];
    }
    public function promptOccasionTypes()
    {
        return ["New Year's Day", "Valentine's Day", "International Women's Day", "Easter", "Independence Day", "Halloween", "Thanksgiving", "Black Friday", "Cyber Monday", "Christmas", "Diwali", "Chinese New Year", "Diwali", "Ramadan", "Eid", "Veteran's Day", "April Fool's Day", "End of Financial Year"];
    }
    public function promptFocusType()
    {
        return ["Membership Selling", "Product Selling", "Traffic Selling"];
    }
    public function generatePrompt($userDetails)
    {
        if (isset($_POST["website_title"]) && isset($_POST["website_type"]) && isset($_POST["occasion_type"]) && isset($_POST["focus_type"]) && isset($_POST["regular_price"]) && isset($_POST["offer_price"]) && isset($_POST["product_title"]) && isset($_POST["csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed."];
            }
            if (empty($_POST["website_title"]) || empty($_POST["website_type"]) || empty($_POST["occasion_type"]) || empty($_POST["focus_type"]) || empty($_POST["regular_price"]) || empty($_POST["product_title"]) || empty($_POST["csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if (!is_numeric($_POST["regular_price"]) || $_POST["regular_price"] < 0) {
                return ["success" => false, "message" => "Invalid regular price."];
            }
            if (!empty($_POST["offer_price"]) && !is_numeric($_POST["offer_price"])) {
                return ["success" => false, "message" => "Invalid offer price."];
            }
            if (!in_array($_POST["website_type"], $this->promptWebsiteTypes())) {
                return ["success" => false, "message" => "Unknown website type."];
            }
            if ($_POST["occasion_type"] != "No Occasion" && !in_array($_POST["occasion_type"], $this->promptOccasionTypes())) {
                return ["success" => false, "message" => "Unknown occasion type."];
            }
            if (!in_array($_POST["focus_type"], $this->promptFocusType())) {
                return ["success" => false, "message" => "Unknown focus type."];
            }
            if (40 <= strlen($_POST["website_title"])) {
                return ["success" => false, "message" => "Website title is too long. You can use maximum 40 characters."];
            }
            if (!empty($_POST["product_title"]) && 40 <= strlen($_POST["product_title"])) {
                return ["success" => false, "message" => "Product title is too long. You can use maximum 40 characters."];
            }
            $membersController = new MembersController();
            if ($membersController->getUserCSRFToken() != $_POST["csrf_token"]) {
                return ["success" => false, "message" => "Invalid request"];
            }
            $chatGPTSettings = $this->getSettings();
            if ($chatGPTSettings["chatGPTStatus"] != 1) {
                return ["success" => false, "message" => "Sorry ! ChatGPT is disabled. Please contact admin."];
            }
            if ($userDetails["chat_gpt_access"] != 1) {
                return ["success" => false, "message" => "Sorry ! You don't have ChatGPT access. Please upgrade your membership."];
            }
            if ($userDetails["membership_end_time"] != "Lifetime" && $userDetails["membership_end_time"] < time()) {
                return ["success" => false, "message" => "Sorry ! Please upgrade or renew your membership to get ChatGPT access."];
            }
            if ($userDetails["chat_gpt_prompt_limit"] <= $this->currentMonthTotalUserPrompt($userDetails["username"])) {
                return ["success" => false, "message" => "Sorry ! You reached your maximum limit for this month. Try again next month or upgrade your account."];
            }
            $chatGPTResponse = $this->chatGPT($_POST["website_title"], $_POST["website_type"], $_POST["occasion_type"], $_POST["focus_type"], $_POST["regular_price"], $_POST["offer_price"], $_POST["product_title"]);
            if (isset($chatGPTResponse["choices"])) {
                $chatGPTMessage = $chatGPTResponse["choices"][0]["message"]["content"];
                $chatGPTPromptTokens = $chatGPTResponse["usage"]["prompt_tokens"];
                $chatGPTCompletionTokens = $chatGPTResponse["usage"]["completion_tokens"];
                $this->model->insertPrompt(["username" => $userDetails["username"], "chat_gpt_response" => base64_encode($chatGPTMessage), "prompt_tokens" => $chatGPTPromptTokens, "completion_tokens" => intval($chatGPTCompletionTokens), "prompt_timestamp" => time()]);
                return ["success" => true, "message" => "ChatGPT has successfully generated your desired email. We have saved the email for you to use later.", "chatGPT_Response" => $chatGPTMessage];
            }
            if (isset($chatGPTResponse["error"]["message"])) {
                return ["success" => false, "message" => "There is an error with ChatGPT functionality. Please contact admin if you are getting this error for multiple times. <br> ChatGPT Error Message : " . $chatGPTResponse["error"]["message"]];
            }
            return ["success" => false, "message" => "There is an error with ChatGPT functionality. Please contact admin if you are getting this error for multiple times."];
        }
    }
    public function currentMonthTotalUserPrompt($username)
    {
        return $this->model->totalUserPromptCurrentMonth($username);
    }
    public function chatGPT($websiteTitle, $websiteType, $occasionType, $emailFocusType, $regularPrice, $offerPrice, $productTitle)
    {
        $url = "https://api.openai.com/v1/chat/completions";
        $chatGPTSettings = $this->getSettings();
        $headers = ["Content-Type: application/json", "Authorization: Bearer " . $chatGPTSettings["api_key"]];
        $occasionText = " ";
        $productTitleText = " ";
        if ($occasionType != "No Occasion") {
            $occasionText = " on the occasion of " . $occasionType;
        }
        if (!empty($productTitle)) {
            if ($occasionType == "Membership Selling") {
                $productTitleText = " and our membership title is '" . $productTitle . "'";
            } else {
                if ($occasionType == "Product Selling") {
                    $productTitleText = " and our product is '" . $productTitle . "'";
                } else {
                    if ($occasionType == "Traffic Selling") {
                        $productTitleText = " and our traffic product title is '" . $productTitle . "'";
                    }
                }
            }
        }
        if (empty($offerPrice) || $offerPrice <= 0) {
            $prompt = "Create a marketing email for a " . $websiteType . " website named '" . $websiteTitle . "' " . $occasionText . " " . $productTitleText . ". The email should focus on " . $emailFocusType . ", highlighting the benefits of our offerings. List the regular price as \$" . $regularPrice . " and represent the price as very attractive. The content should be engaging and persuasive, encouraging customers to take advantage of the offering. Include both the email content and subject and avoid adding unsubscribe or other texts.";
        } else {
            $prompt = "Create a marketing email for a " . $websiteType . " website named '" . $websiteTitle . "' " . $occasionText . " " . $productTitleText . ". The email should focus on " . $emailFocusType . ". List the regular price as \$" . $regularPrice . " and the exclusive offer price as \$" . $offerPrice . ". The email should effectively communicate the benefits of the offer and engage the customers. Include both the email content and subject and avoid adding unsubscribe or other texts.";
        }
        $postData = ["model" => $chatGPTSettings["open_ai_model"], "messages" => [["role" => "user", "content" => $prompt]], "temperature" => 0, "max_tokens" => intval($chatGPTSettings["max_tokens"])];
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }
    public function openAIModels()
    {
        return ["gpt-4-1106-preview", "gpt-4", "gpt-3.5-turbo-1106"];
    }
    public function openAIModelPrices()
    {
        return ["gpt-4-1106-preview" => "Will cost maximum \$0.03 Per Email Generation with 1000 Max Tokens", "gpt-4" => "Will cost maximum \$0.06 Per Email Generation with 1000 Max Tokens", "gpt-3.5-turbo-1106" => "Will cost maximum \$0.0020 Per Email Generation with 1000 Max Tokens"];
    }
}

?>