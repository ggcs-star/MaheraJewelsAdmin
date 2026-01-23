<?php

if (!function_exists('admin_route')) {
    /**
     * Generate a route URL for admin routes.
     * Automatically prepends 'admin.' prefix to route names.
     *
     * @param string $name
     * @param mixed $parameters
     * @param bool $absolute
     * @return string
     */
    function admin_route($name, $parameters = [], $absolute = true)
    {
        // If route name already starts with 'admin.', use it as is
        if (str_starts_with($name, 'admin.')) {
            return route($name, $parameters, $absolute);
        }

        // Otherwise, prepend 'admin.' prefix
        return route('admin.' . $name, $parameters, $absolute);
    }
}
