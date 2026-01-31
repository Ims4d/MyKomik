<?php

namespace App\Helpers;

class AvatarHelper
{
    /**
     * Generate avatar URL for user
     * Returns custom avatar if exists, otherwise generates initials avatar
     */
    public static function getAvatarUrl($user)
    {
        // If user has custom avatar, return it
        if ($user->avatar) {
            return $user->avatar;
        }

        // Otherwise, generate initials avatar URL
        return self::generateInitialsAvatarUrl($user);
    }

    /**
     * Generate initials from user's name
     */
    public static function getInitials($user)
    {
        $name = $user->display_name ?? $user->username;
        
        // Split name by space
        $nameParts = explode(' ', trim($name));
        
        if (count($nameParts) >= 2) {
            // First and last name: "John Doe" -> "JD"
            return strtoupper(substr($nameParts[0], 0, 1) . substr(end($nameParts), 0, 1));
        } else {
            // Single name: "John" -> "JO" or "J"
            $name = $nameParts[0];
            if (strlen($name) >= 2) {
                return strtoupper(substr($name, 0, 2));
            } else {
                return strtoupper(substr($name, 0, 1));
            }
        }
    }

    /**
     * Generate a consistent color based on username
     */
    public static function getAvatarColor($username)
    {
        // Predefined nice colors
        $colors = [
            '#e74c3c', // Red
            '#3498db', // Blue
            '#2ecc71', // Green
            '#f39c12', // Orange
            '#9b59b6', // Purple
            '#1abc9c', // Turquoise
            '#e67e22', // Carrot
            '#34495e', // Dark Blue
            '#16a085', // Green Sea
            '#c0392b', // Dark Red
            '#2980b9', // Belize Blue
            '#8e44ad', // Wisteria
        ];

        // Use hash of username to pick consistent color
        $hash = crc32($username);
        $index = abs($hash) % count($colors);
        
        return $colors[$index];
    }

    /**
     * Generate UI Avatars URL (external service)
     * Alternative method using external API
     */
    public static function generateInitialsAvatarUrl($user)
    {
        $initials = self::getInitials($user);
        $color = self::getAvatarColor($user->username);
        
        // Remove # from color
        $color = str_replace('#', '', $color);
        
        // Use UI Avatars API
        // https://ui-avatars.com/
        $params = [
            'name' => urlencode($initials),
            'background' => $color,
            'color' => 'ffffff', // White text
            'size' => 200,
            'bold' => true,
            'format' => 'svg'
        ];
        
        $queryString = http_build_query($params);
        return "https://ui-avatars.com/api/?{$queryString}";
    }

    /**
     * Generate inline SVG avatar (no external dependency)
     * Use this if you don't want to rely on external service
     */
    public static function generateInlineSvgAvatar($user, $size = 200)
    {
        $initials = self::getInitials($user);
        $color = self::getAvatarColor($user->username);
        $fontSize = $size * 0.4; // 40% of size
        
        $svg = <<<SVG
<svg width="{$size}" height="{$size}" xmlns="http://www.w3.org/2000/svg">
    <rect width="{$size}" height="{$size}" fill="{$color}"/>
    <text x="50%" y="50%" font-family="Arial, sans-serif" font-size="{$fontSize}" font-weight="bold" fill="#ffffff" text-anchor="middle" dy=".35em">{$initials}</text>
</svg>
SVG;
        
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
}