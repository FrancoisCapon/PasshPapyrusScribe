<?php

class PasshPhrase {

    function __construct($passhCommand, $osCommand, $dialog, $withExit = True)
    {
        $this->passhCommand = $passhCommand; // with extra option(s) if needed
        $this->osCommand = $osCommand;
        $this->dialog = $dialog; // [prompt, command, prompt, command, ...]
        $this->withExit = $withExit; // exit controller or not
    }

    function __toString()
    {
        $toString = $this->osCommand;
        $currentPromptString = '';
        $currentPromptCount = 0;
        $lastPromptIndex = count($this->dialog) - 2;
        for ($dialogIndex = 0; $dialogIndex <= $lastPromptIndex; $dialogIndex += 2) 
        {
            $prompt = $this->dialog[$dialogIndex];
            $command = $this->dialog[$dialogIndex + 1];
            $toString = "-P '$prompt' -p '$command' $toString";
            if ($prompt != $currentPromptString) 
            {
                $currentPromptString = $prompt;
                $currentPromptCount = 1;
            } else {
                $currentPromptCount++;
            }
            if ($dialogIndex < $lastPromptIndex ) {
                $toString = "-c 1 " . $toString;
            } else {
                $toString = "-c $currentPromptCount " . $toString;
                if ($this->withExit)
                {
                    $toString = "-C " . $toString;
                }
            }
            $toString = $this->passhCommand . ' ' . $toString;
        }
        return $toString;
    }
}

class PassParagraph {
    function __construct(array $phrases) {
        $this->phrases = $phrases;
    }

    function __toString()
    {
        $toString = '';
        foreach($this->phrases as $phrase) 
        {
            return implode(' ; ',$this->phrases);
        }
    }
}
