<?php

class ApprovalWorkflow {
    private $currentState;
    private $transitions = [];

    public function __construct() {
        $this->currentState = "draft";
        $this->transitions = [
            "draft" => ["submit" => "pending_approval"],
            "pending_approval" => [
                "approve" => "approved",
                "reject" => "rejected"
            ],
            "rejected" => ["reset" => "pending_approval"],
            "approved" => ["publish" => "published"]
        ];
    }

    public function transition($action, $is_admin = false, $is_expired = false) {
        if ($this->isValidTransition($action, $is_admin, $is_expired)) {
            $this->currentState = $this->transitions[$this->currentState][$action];
            return true;
        }
        return false;
    }

    private function isValidTransition($action, $is_admin, $is_expired) {
        switch ($this->currentState) {
            case "draft":
                return $action === "submit";
            case "pending_approval":
                return $action === "approve" || $action === "reject";
            case "rejected":
                return $action === "reset";
            case "approved":
                return $action === "publish" && ($is_admin || !$is_expired);
            default:
                return false;
        }
    }

    public function getCurrentState() {
        return $this->currentState;
    }
}

// 示例使用
$workflow = new ApprovalWorkflow();
$workflow->transition("submit");
$workflow->transition("approve", true);
$workflow->transition("publish");

echo "Current State: " . $workflow->getCurrentState() . PHP_EOL;
