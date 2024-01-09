<?php
class EventDrivenStateMachine {
    private $state;
    private $transitions = [];

    public function __construct($initialState) {
        $this->state = $initialState;
    }

    public function addTransition($fromState, $event, $toState, $callback) {
        $this->transitions[$fromState][$event] = [
            "toState" => $toState,
            "callback" => $callback,
        ];
    }

    public function triggerEvent($event) {
        $transition = $this->transitions[$this->state][$event] ?? null;
        if ($transition) {
            $this->state = $transition["toState"];
            $callback = $transition["callback"];
            if ($callback) {
                $callback();
            }
            return true;
        }
        return false;
    }

    public function getCurrentState() {
        return $this->state;
    }
}

// Usage
$workflow = new EventDrivenStateMachine("initial");
$workflow->addTransition("initial", "next", "pending_approval", function() {
    echo "Transitioned to Pending Approval" . PHP_EOL;
});
$workflow->addTransition("pending_approval", "approve", "approved", function() {
    echo "Transitioned to Approved" . PHP_EOL;
});

$workflow->triggerEvent("next");
echo "Current State: " . $workflow->getCurrentState() . PHP_EOL;

$workflow->triggerEvent("approve");
echo "Current State: " . $workflow->getCurrentState() . PHP_EOL;
