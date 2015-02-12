<?php namespace Torann\Moderate;

class Moderator
{
    /**
     * Package Config
     *
     * @var array
     */
    protected $config = array();

    /**
     * Blacklist
     *
     * @var array
     */
    public $blackList;

    /**
     * Class constructor.
     *
     * @param  array $config
     * @param  array $blackList
     */
    function __construct(array $config, array $blackList)
    {
        $this->config    = $config;
        $this->blackList = $blackList;
    }

    /**
     * Checks the text if it contains any word that is blacklisted.
     *
     * @param string $text
     *
     * @return bool
     */
    public function blacklist($text)
    {
        // No blacklist then there isn't much to do
        if (empty($this->blackList)) {
            return false;
        }
        // Normalize string before passing
        $text = $this->prepare($text);

        // Remove special characters
        $text = preg_replace("/[^a-zA-Z0-9-\.]/", "", $text);

        $blackListRegex = sprintf('!%s!', implode('|', array_map(function ($value)
        {
            if (isset($value[0]) && $value[0] == '[')
            {
                $value = substr($value, 1, -1);
            }
            else {
                $value = preg_quote($value);
            }

            return '(?:' . $value . ')';

        }, $this->blackList)));

        return (bool)preg_match($blackListRegex, $text);
    }

    /**
     * Checks the text if it contains any word that is blacklisted.
     *
     * @param string $text
     * @param string $maxLinkAllowed
     *
     * @return bool
     */
    public function links($text, $maxLinkAllowed)
    {
        $maxLinkAllowed = $maxLinkAllowed ?: $this->config["defaultMaxLinks"];

        // Normalize string before passing
        $text = $this->prepare($text);

        preg_match_all("!((https?://)?([-\w]+\.[-\w\.]+)+\w(:\d+)?(/([-\w/_\.]*(\?\S+)?)?)*)!", $text, $matches);
        $linkCount = count($matches[0]);

        return ($linkCount >= (int) $maxLinkAllowed);
    }

    /**
     * Used to normalize string before passing
     * it to detectors
     *
     * @param string $text
     *
     * @return string
     */
    public function prepare($text)
    {
        if (isset($this->config["asciiConversion"]))
        {
            setlocale(LC_ALL, 'en_us.UTF8');

            $text = iconv('UTF-8', 'ASCII//TRANSLIT', $text);
        }

        $text = trim(strtolower($text));
        $text = str_replace(array("\t", "\r\n", "\r", "\n"), "", $text);

        // Convert some characters that 'MAY' be used as alias
        $text = str_replace(array("@", "$", "[dot]", "(dot)"), array("at", "s", ".", "."), $text);

        // Strip multiple dots (.) to one. eg site......com to site.com
        $text = preg_replace("/\.{2,}/", ".", $text);

        return $text;
    }
}
