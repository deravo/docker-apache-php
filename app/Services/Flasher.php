<?php namespace App\Services;

use Session;

class Flasher {

    /**
     * Store the flash.
     *
     * @param  string $message
     * @param  string $level
     * @return $this
     */
    public function message($message, $level = 'info')
    {
        Session::flash('flash_notification.message', $message);
        Session::flash('flash_notification.level', $level);

        return $this;
    }

    /**
     * Flash an info.
     *
     * @param  string $message
     * @return $this
     */
    public function info($message)
    {
        $this->message($message, 'info');

        return $this;
    }

    /**
     * Flash a success.
     *
     * @param  string $message
     * @return $this
     */
    public function success($message)
    {
        $this->message($message, 'success');

        return $this;
    }

    /**
     * Flash an error.
     *
     * @param  string $message
     * @return $this
     */
    public function error($message)
    {
        $this->message($message, 'danger');

        return $this;
    }

    /**
     * Flash a warning.
     *
     * @param  string $message
     * @return $this
     */
    public function warning($message)
    {
        $this->message($message, 'warning');

        return $this;
    }

    /**
     * Make the flash alert ephemeral.
     *
     * @return $this
     */
    public function ephemeral()
    {
        Session::flash('flash_notification.ephemeral', true);

        return $this;
    }

    /**
     * Make the flash alert dismissable.
     *
     * @return $this
     */
    public function dismissable()
    {
        Session::flash('flash_notification.dismissable', true);

        return $this;
    }

}
