<?php 

  function lang($phrase) {

    static $lang = array(

      'key' => 'welcome'

    );
    return $lang['phrase'];
  }