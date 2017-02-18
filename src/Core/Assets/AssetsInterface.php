<?php
/**
 * Created by PhpStorm.
 * User: marcp
 * Date: 18/02/2017
 * Time: 17:11
 */

namespace Core\Assets;


interface AssetsInterface {

	public function __construct();

	public function registerFile($file);

	public function registerPlain($plain);

	public function dump();

}