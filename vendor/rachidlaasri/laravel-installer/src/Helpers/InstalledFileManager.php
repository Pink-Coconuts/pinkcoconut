<?php

namespace RachidLaasri\LaravelInstaller\Helpers;


class InstalledFileManager
{
    /**
     * Create installed file.
     *
     * @return int
     */
    public function create()
    {
        file_put_contents(storage_path('installed'), '');

        $result = file_get_contents(base_path('.env'));
        $newLine = $result."SESSION_DRIVER=database\n";
        file_put_contents(base_path('.env'), $newLine);
    }

    /**
     * Update installed file.
     *
     * @return int
     */
    public function update()
    {
        return $this->create();
    }
}