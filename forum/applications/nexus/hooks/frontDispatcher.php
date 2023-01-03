//<?php

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	exit;
}

class nexus_hook_frontDispatcher extends _HOOK_CLASS_
{
	/**
	 * Output the basic javascript files every page needs
	 *
	 * @return void
	 */
	protected static function baseJs()
	{
		parent::baseJs();

		if ( !\IPS\Request::i()->isAjax() )
		{
			if ( \IPS\Settings::i()->gateways_counts and $decoded = json_decode( \IPS\Settings::i()->gateways_counts, TRUE ) and isset( $decoded['Stripe'] ) and $decoded['Stripe'] > 0 )
			{
				\IPS\Output::i()->jsFiles[] = 'https://js.stripe.com/v3/';
			}
		}
	}
}
