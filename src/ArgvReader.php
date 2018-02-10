<?php

namespace IDCT\Cli;

/**
 * Basic command line arguments reader.
 *
 */
class ArgvReader {

    /**
     * Array of arguments in the order as they appeared in the cli string.
     *
     * @var string[]
     */
    private $arguments;

    /**
     * Associative array of options: key is the option's name.
     *
     * @var string[string]
     */
    private $options;

    /** Array of flags. Stored as associative array where flag is the key.
     *
     * @var boolean[string]
     */
    private $flags;

    /**
     * Performs the actual parsing and storing into internal arrays.
     *
     * @return $this
     */
    protected function parseArgv()
    {
        global $argv;
        $cliArgv = $argv;
        array_shift($cliArgv);

        $arguments = [];
        $options = [];
        $flags = [];

        foreach($cliArgv as $candidate) {
            $matches = [];
            if(preg_match("/--([a-z0-9]+?)=(.*)/i", $candidate, $matches)) { //option
                $options[$matches[1]] = $matches[2];
            } elseif (preg_match("/--([a-z0-9^=]+?)$/i", $candidate, $matches)) { //flag
                //TODO long flags
                $flags[$matches[1]] = true;
            } else {
                $arguments[] = $candidate;
                //argument
            }
        }

        $this->arguments = $arguments;
        $this->options = $options;
        $this->flags = $flags;

        return $this;
    }

    /**
     * Returns an array with all the arguments.
     *
     * Performs lazyloading of arguments on first call.
     *
     * @return string[]
     */
    public function getArguments() {
        if ($this->arguments === null) {
            $this->parseArgvs();
        }

        return $this->arguments;
    }

    /**
     * Returns an associative array of options with values.
     *
     * @return string[string]
     */
    public function getOptions() {
        if ($this->options === null) {
            $this->parseArgvs();
        }

        return $this->options;
    }

    /**
     * Returns an associative array of flags where flag is the key.
     *
     * @return boolean[string]
     */
    public function getFlags() {
        if ($this->flags === null) {
            $this->parseArgvs();
        }

        return $this->flags;
    }

    /**
     * Returns argument at a given position, if not set returns null.
     *
     * @return string|null
     */
    public function getArgument($position) {
        if (isset($this->getArguments()[$position])) {
            return $this->getArguments()[$position];
        }

        return null;
    }

    /**
     * Returns option's value, null if not set.
     *
     * @return string|null
     */
    public function getOption($name) {
        if (isset($this->getOptions()[$name])) {
            return $this->getOptions()[$name];
        }

        return null;
    }

    /**
     * Retruns true or false if flag is set or not.
     *
     * @return boolean
     */
    public function hasFlag($name) {
        if (isset($this->getFlags()[$name])) {
            true;
        }

        return false;
    }
}
