<?php

require_once 'Horde/String.php';

/**
 * The Horde_SpellChecker:: class provides a unified spellchecker API.
 *
 * Copyright 2005-2009 The Horde Project (http://www.horde.org/)
 *
 * See the enclosed file COPYING for license information (LGPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 *
 * @author  Chuck Hagenbuch <chuck@horde.org>
 * @author  Michael Slusarz <slusarz@horde.org>
 * @package Horde_SpellChecker
 */
abstract class Horde_SpellChecker
{
    const SUGGEST_FAST = 1;
    const SUGGEST_NORMAL = 2;
    const SUGGEST_SLOW = 3;

    /**
     * @var integer
     */
    protected $_maxSuggestions = 10;

    /**
     * @var integer
     */
    protected $_minLength = 3;

    /**
     * @var string
     */
    protected $_locale = 'en';

    /**
     * @var string
     */
    protected $_encoding = 'utf-8';

    /**
     * @var boolean
     */
    protected $_html = false;

    /**
     * @var integer
     */
    protected $_suggestMode = self::SUGGEST_FAST;

    /**
     * @var array
     */
    protected $_localDict = array();

    /**
     * Attempts to return a concrete Horde_SpellChecker instance based on
     * $driver.
     *
     * @param string $driver  The type of concrete Horde_SpellChecker subclass
     *                        to return.
     * @param array $params   A hash containing any additional configuration or
     *                        connection parameters a subclass might need.
     *
     * @return Horde_SpellChecker  The newly created Horde_SpellChecker
     *                             instance.
     * @throws Exception
     */
    static public function getInstance($driver, $params = array())
    {
        $class = 'Horde_SpellChecker_' . String::ucfirst(basename($driver));
        if (!class_exists($class)) {
            throw new Exception('Driver ' . $driver . ' not found');
        }
        return new $class($params);
    }

    /**
     * Constructor.
     */
    public function __construct($params = array())
    {
        $this->setParams($params);
    }

    /**
     * TODO
     *
     * @param array $params  TODO
     */
    public function setParams($params)
    {
        foreach ($params as $key => $val) {
            $key = '_' . $key;
            $this->$key = $val;
        }
    }

    /**
     * TODO
     *
     * @param string $text  TODO
     *
     * @return array  TODO
     * @throws Exception
     */
    abstract public function spellCheck($text);

    /**
     * TODO
     *
     * @param string $text  TODO
     *
     * @return array  TODO
     */
    protected function _getWords($text)
    {
        return array_keys(array_flip(preg_split('/[\s\[\]]+/s', $text, -1, PREG_SPLIT_NO_EMPTY)));
    }

    /**
     * Determine if a word exists in the local dictionary.
     *
     * @param string $word  The word to check.
     *
     * @return boolean  True if the word appears in the local dictionary.
     */
    protected function _inLocalDictionary($word)
    {
        return (empty($this->_localDict))
            ? false
            : in_array(String::lower($word, true), $this->_localDict);
    }

}
