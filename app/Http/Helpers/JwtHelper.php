<?php

/**
 * Concat two string.
 *
 * @param  string $token
 * @return object
 */
function jwtToUser($token)
{
	return \JWTAuth::toUser($token);
}