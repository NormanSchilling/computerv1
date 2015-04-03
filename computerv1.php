#!/usr/bin/php
<?php

main( $argc, $argv );

function check_zero( $part )
{
	if ( $part == "0" )
		return ( 1 );
	return ( 0 );
}

function simplification( $left, $right )
{
	echo "ETAPE: simplification\n";
	$a_simplifier = array();
	$equation = array();

	for ($i = 0; $i < count($left["power"]); $i++)
	{
		$a_simplifier["quotient"][$left["power"][$i]][] =  $left["quotient"][$i];
	}

	for ($i = 0; $i < count($right["power"]); $i++)
	{
		$a_simplifier["quotient"][$right["power"][$i]][] =  $right["quotient"][$i];
	}
	$j = 0;
	foreach ($a_simplifier["quotient"] as $key => $value)
	{

		$equation["power"][] = $key;
		if (count($value) == 1)
			$equation["quotient"][$j] = $value[0];
		else
			$equation["quotient"][$j] = $value[0] - $value[1];
		$j++;
	}
	message_reduced($equation);
	return ($equation);
}

function reduce_part( $part )
{
	$a_part = array();
	$tmp_quotient = array();
	$tmp_power = array();

	$a_part["power"] = parse_power( $part );
	$a_part["quotient"] = parse_quotient( $part );
	$tmp = $a_part["power"][0];
	$j = 0;
	for ($i = 0; $i < count($a_part["power"]); $i++)
	{
		if ($a_part["power"][$i] == $tmp)
		{
			$tmp_quotient[$j] = $tmp_quotient[$j] + $a_part["quotient"][$i];
			$tmp_power[$j] = $a_part["power"][$i];
		}
		else
		{
			$j++;
			$tmp_quotient[$j] = $a_part["quotient"][$i];
			$tmp_power[$j] = $a_part["power"][$i];

		}
		$tmp = $a_part["power"][$i];
	}
	$a_part["quotient"] = $tmp_quotient;
	$a_part["power"] = $tmp_power;
	return ($a_part);
}

function parse_power( $part )
{
	preg_match_all( "/X\^([0-9]{1,})/", $part, $power );
	return( $power[1] );
}

function parse_quotient( $part )
{
	preg_match_all( "/([-]{0,1}[0-9]{1,}[.]{0,}[0-9]*)\*/", $part, $quotient );
	return( $quotient[1] );
}

function resolved_first_degree( $equation )
{
	echo "ETAPE: resolve first degree\n";
	if (count($equation["power"]) == 2 )
		$result = - $equation["quotient"][0] / - $equation["quotient"][1];
	else
		$result = - $equation["quotient"][0];
	echo "The solution is :";
	echo $result;
}

function resolved_second_degree( $equation )
{
	echo "ETAPE: resolve second degree\n";
	for ($i = 0; $i < count($equation["power"]); $i++)
	{
		if ($equation["power"][$i] == 0)
		{
			$c = $equation["quotient"][$i];
		}
		if ($equation["power"][$i] == 1)
		{
			$b = $equation["quotient"][$i];
		}
		if ($equation["power"][$i] == 2)
		{
			$a = $equation["quotient"][$i];
		}
	}
	if ($b == NULL)
		$b = 0;
	if ($c == NULL)
		$c = 0;

	$delta = $b * $b - 4 * $a * $c;
	echo "delta = " . $delta . "\n";
	if ( $delta == 0 )
	{
		$result = - $b / (2 * $a);
		echo "The solution is :\n";
		echo $result."\n";
	}
	else if ( $delta > 0 )
	{
		$result = ( - $b - ft_sqrt( $delta ) ) / (2 * $a);
		$result2 = ( - $b + ft_sqrt( $delta ) ) / (2 * $a);
		echo "Discriminant is strictly positive, the two solutions are :\n";
		echo $result."\n";
		echo $result2."\n";
	}
	else
	{
		$calc1 = $b / (2 * $a);
		$calc2 = ft_sqrt( - $delta ) / (2 * $a);
		echo "Discriminant is strictly negatif, they are two solutions with imaginary number:\n";
		echo $calc1 . " + " . $calc2 . " * i\n";
		echo $calc1 . " - " . $calc2 . " * i\n";
	}
}

function all_real_solutions( $left, $right )
{
	if ( count($left["power"]) == 1 && count($right["power"]) == 1)
	{
		if ($left["quotient"][0] == $right["quotient"][0] && $left["power"][0] == $right["power"][0])
		{
			echo "Reduced Form: " . $left["quotient"][0] . " * X^" . $left["power"][0] . " = " . $right["quotient"][0] . " * X^" . $right["power"][0] . "\n";
			echo "all number real are solutions\n";
			return ( true );
		}
	}
	return ( false );
}

function impossible_solutions( $left, $right )
{
	if ( count($left["power"]) == 1 && count($right["power"]) == 1)
	{
		if ($left["quotient"][0] != $right["quotient"][0] )
		{
			echo "Reduced Form: " . $left["quotient"][0] . " * X^" . $left["power"][0] . " = " . $right["quotient"][0] . " * X^" . $right["power"][0] . "\n";
			echo "They are no solutions\n";
			return ( true );
		}
	}
	return ( false );
}

function message_reduced( $a_simplifier )
{
	echo "Reduced Form: ";
	for ($i = 0; $i < count($a_simplifier["quotient"]); $i++)
	{
		if ( $i + 1 != count($a_simplifier["quotient"]) )
		{
			if ($a_simplifier["quotient"][$i + 1] > 0)
				echo $a_simplifier["quotient"][$i] . " * X^" .  $a_simplifier["power"][$i] . "+";
			else
				echo $a_simplifier["quotient"][$i] . " * X^" .  $a_simplifier["power"][$i];
		}
		else
			echo $a_simplifier["quotient"][$i] . " * X^" .  $a_simplifier["power"][$i];
	}
	echo " = 0\n";
}

function message_degree( $degree )
{
	if ( $degree < 2 )
		echo "Polynomial degree : 1\n";
	else if ( $degree == 2 )
		echo "Polynomial degree : 2\n";
	else
	{
		echo "Polynomial degree : " . $degree . "\n";
		echo "The polynomial degree is stricly greater than 2, I can't solve\n";
	}
}

function ft_sqrt($x)
{
	if ($x == 0 || $x == 1)
		return ($x);
	$a = $x;
	for ($i = 0; $i < 10; $i++)
	{
		$tmp = ( $a + ($x) / $a ) / 2;
		$a = $tmp;
	}
	return ($a);
}

function get_degree( $equation )
{
	for ($i = 0; $i < count($equation["power"]); $i++)
	{
		$degree = $equation["power"][$i];
	}
	return ($degree);
}

function main( $argc, $argv )
{
	$equation = array();
	if ( $argc == 2 )
	{
		$check = preg_replace( "/[X\^0-9\.\*=\+\-\s]/" , "" , $argv[1] );
		if ( strlen($check) > 0 )
		{
			echo "they are char no authorized : " . $check . "\n";
			return ;
		}
		$array_part = explode( '=', str_replace ( " ", "", $argv[1] ) );
		$left = reduce_part($array_part[0]);
		$right = reduce_part($array_part[1]);
		if ( all_real_solutions($left, $right) == false && impossible_solutions($left, $right) == false )
		{
			if ( check_zero( $array_part[0] ) == 0 && check_zero( $array_part[1] ) == 0)
				$equation = simplification( $left, $right );
			else if ($left["power"] != NULL)
				$equation = $left;
			else if ($right["power"] != NULL)
				$equation = $right;
			$degree = get_degree( $equation );
			message_degree( $degree );
			if ( $degree < 3 )
			{
				if ( $degree < 2 )
					resolved_first_degree( $equation );
				else if ($degree == 2)
				{
					resolved_second_degree( $equation );
				}
			}
		}
	}
	else
		echo "Error: you need to write a equation in argument !";
}

?>
