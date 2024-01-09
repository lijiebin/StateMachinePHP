class State {
    private $name;
    private $transitions = [];

    public function __construct($name) {
        $this->name = $name;
    }

    public function addTransition($action, $targetState) {
        $this->transitions[$action] = $targetState;
    }

    public function getTargetState($action) {
        return $this->transitions[$action] ?? null;
    }
}

class StateMachine {
    private $states = [];
    private $currentState;

    public function addState($name) {
        $this->states[$name] = new State($name);
    }

    public function addTransition($fromState, $action, $toState) {
        $this->states[$fromState]->addTransition($action, $toState);
    }

    public function transition($action) {
        $targetState = $this->currentState->getTargetState($action);
        if ($targetState) {
            $this->currentState = $this->states[$targetState];
            return true;
        }
        return false;
    }

    public function setCurrentState($stateName) {
        $this->currentState = $this->states[$stateName];
    }

    public function getCurrentState() {
        return $this->currentState->getName();
    }
}

// Usage
$workflow = new StateMachine();
$workflow->addState("initial");
$workflow->addState("pending_approval");
$workflow->addState("approved");

$workflow->addTransition("initial", "next", "pending_approval");
$workflow->addTransition("pending_approval", "approve", "approved");

$workflow->setCurrentState("initial");
$workflow->transition("next");
echo "Current State: " . $workflow->getCurrentState() . PHP_EOL;

$workflow->transition("approve");
echo "Current State: " . $workflow->getCurrentState() . PHP_EOL;
