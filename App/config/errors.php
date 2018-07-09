<?php

/* 	we will use the environment which is set within the config file
 	besides that this will only be loaded if the error-logging is set the Handler

	display: all will show all errors, important will hide notice and warnings
	mail:
		sendMail => true will send an email to the administrator of the application for an error flase will not send one
		onlyUnique => true only new errors will be send, false all the error will be mailed
	logs: we wil log the errors to the logs/errors.txt file

*/
return [
	'development' => ['mail' => ['sendMail' => false, 'onlyUnique' => true],
						'logs' => [	'log_errors' => ['log'=>true,'location'=>'/logs/errors.txt'],
									'log_exceptions'=>['log'=>true,'location'=>'/logs/exceptions.txt']]],

	'live' => ['mail' => ['sendMail' => true, 'onlyUnique' => true],
				'logs' => [	'log_errors' => ['log'=>true,'location'=>'logs/errors.txt'],
							'log_exceptions'=>['log'=>true,'location'=>'logs/exceptions.txt']]]
];