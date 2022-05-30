<?php


/**
 * @param string $price
 * @return string
 */
function str_price(?string $price): string
{
    return number_format((!empty($price) ? $price : 0), 2, ',', '.');
}

/**
 * ###############
 * ###   URL   ###
 * ###############
 */

/**
 * @param string $path
 * @return string
 */
function url(string $path = null): string
{
    if ($path) {
        return CONF_URL_BASE . "/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
    }

    return CONF_URL_BASE;
}

/**
 * @param string $url
 */
function redirect(string $url): void
{
    header("HTTP/1.1 302 Redirect");
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        header("Location: {$url}");
        exit;
    }

    if (filter_input(INPUT_GET, "route", FILTER_DEFAULT) != $url) {
        $location = url($url);
        header("Location: {$location}");
        exit;
    }
}

/**
 * ##################
 * ###   ASSETS   ###
 * ##################
 */


/**
 * @param string|null $path
 * @param bool $shared
 * @param string $theme
 * @param bool $time
 * @return string
 */
function theme(string $path, bool $shared = false, string $theme = CONF_VIEW_THEME, bool $time = true): string
{
    if (!$shared) {
        $file = CONF_URL_BASE . "/themes/{$theme}/assets/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
        $fileOnDir = dirname(__DIR__, 2) . "/themes/{$theme}/assets/{$path}";
    } else {
        $file = CONF_URL_BASE . "/shared/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
        $fileOnDir = dirname(__DIR__, 2) . "/shared/{$path}";
    }

    if ($time && file_exists($fileOnDir)) {
        $file .= "?time=" . filemtime($fileOnDir);
    }
    return $file;
}

/**
 * @param string $path
 * @param bool $shared
 * @param string $theme
 * @param bool $time
 * @return string
 */
function themeImage(string $path, bool $shared = false, string $theme = CONF_VIEW_THEME, bool $time = true): string
{
    if (!$shared) {
        $file = CONF_URL_BASE . "/themes/images/{$theme}/assets/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
        $fileOnDir = dirname(__DIR__, 2) . "/themes/images/{$theme}/assets/{$path}";
    } else {
        $file = CONF_URL_BASE . "/shared/images/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
        $fileOnDir = dirname(__DIR__, 2) . "/shared/images/{$path}";
    }

    return $file;
}

/**
 * @param string $image
 * @param int $width
 * @param int|null $height
 * @return string
 */
function image(?string $image, int $width, int $height = null): ?string
{
    if ($image) {
        return url() . "/" . (new \Source\Support\Thumb())->make($image, $width, $height);
    }

    return null;
}

/**
 * @param string $number
 * @param int $decimals
 * @param string $decimal_separator
 * @param string $thousands_separator
 * @return string
 */
function number_fmt(
    ?string $number,
    int $decimals = 2,
    string $decimal_separator = ',',
    string $thousands_separator = '.'
): string {
    if (!$number) {
        $number = 0;
    }
    return number_format($number, $decimals, $decimal_separator, $thousands_separator);
}

/**
 * @return string|null
 */
function flash(): ?string
{
    $session = new \Source\Core\Session();
    if ($flash = $session->flash()) {
        echo $flash;
    }

    return null;
}

/**
 * @param string $haystack
 * @param string $needle
 * @return bool
 */
function str_contains(string $haystack, string $needle): bool
{
    return '' === $needle || false !== strpos($haystack, $needle);
}