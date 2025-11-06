<?php
/**
 * WordPress database adapter
 *
 * @package SolveBeam\WpdbX
 * @author SolveBeam
 * @copyright 2025 SolveBeam
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0 or later
 */

declare(strict_types=1);

namespace SolveBeam\WpdbX;

use wpdb;

/**
 * WordPress database adapter class
 */
final class WpdbAdapter {
	/**
	 * WordPress database object.
	 *
	 * @var wpdb
	 */
	private wpdb $wpdb;

	/**
	 * Construct database wrapper.
	 *
	 * @param wpdb $wpdb WordPress database object.
	 */
	public function __construct( wpdb $wpdb ) {
		$this->wpdb = $wpdb;
	}

	/**
	 * Create database wrapper from global $wpdb.
	 *
	 * @return self
	 */
	public static function from_global_wpdb(): self {
		global $wpdb;

		return new self( $wpdb );
	}

	/**
	 * Create database wrapper.
	 *
	 * @return self
	 */
	public static function create() {
		return self::from_global_wpdb();
	}

	/**
	 * Insert.
	 *
	 * @link https://developer.wordpress.org/reference/classes/wpdb/insert/
	 * @link https://github.com/WordPress/wordpress-develop/blob/6.7/src/wp-includes/class-wpdb.php#L2498-L2500
	 * @param string     $table  Table name.
	 * @param array      $data   Data.
	 * @param array|null $format Format.
	 * @return int|false The number of rows inserted, or false on error.
	 */
	public function insert( $table, $data, $format = null ) {
		$result = $this->wpdb->insert( $table, $data, $format );

		if ( false === $result ) {
			throw new \Exception(
				\sprintf(
					'Error inserting into table %s: %s. Data: %s',
					$table,
					$this->wpdb->last_error,
					\wp_json_encode( $data )
				)
			);
		}

		return $result;
	}

	/**
	 * Update.
	 *
	 * @link https://developer.wordpress.org/reference/classes/wpdb/update/
	 * @link https://github.com/WordPress/wordpress-develop/blob/6.7/src/wp-includes/class-wpdb.php#L2674-L2717
	 * @param string     $table        Table name.
	 * @param array      $data         Data.
	 * @param array      $where        Where.
	 * @param array|null $format       Format.
	 * @param array|null $where_format Where format.
	 * @return int|false The number of rows updated, or false on error.
	 */
	public function update( $table, $data, $where, $format = null, $where_format = null ) {
		$result = $this->wpdb->update( $table, $data, $where, $format, $where_format );

		if ( false === $result ) {
			throw new \Exception(
				\sprintf(
					'Error updating table %s: %s. Data: %s, Where: %s',
					$table,
					$this->wpdb->last_error,
					\wp_json_encode( $data ),
					\wp_json_encode( $where )
				)
			);
		}

		return $result;
	}

	/**
	 * Query.
	 *
	 * @link https://developer.wordpress.org/reference/classes/wpdb/query/
	 * @param string $query Query.
	 * @return int|bool
	 */
	public function query( $query ) {
		$result = $this->wpdb->query( $query );

		if ( false === $result ) {
			throw new \Exception(
				\sprintf(
					'Error query %s: %s.',
					$query,
					$this->wpdb->last_error
				)
			);
		}

		return $result;
	}

	/**
	 * Get var.
	 *
	 * @link https://developer.wordpress.org/reference/classes/wpdb/get_var/
	 * @link https://github.com/WordPress/wordpress-develop/blob/6.7/src/wp-includes/class-wpdb.php#L3019-L3037
	 * @param string|null $query Optional. SQL query. Defaults to null, use the result from the previous query.
	 * @param int         $x     Optional. Column of value to return. Indexed from 0. Default 0.
	 * @param int         $y     Optional. Row of value to return. Indexed from 0. Default 0.
	 * @return string|null Database query result (as string), or null on ~~failure~~ no result.
	 */
	public function get_var( $query = null, $x = 0, $y = 0 ) {
		$result = $this->wpdb->get_var( $query, $x, $y );

		/**
		 * The WordPress core documentation states:
		 *
		 * > Database query result (as string), or null on failure.
		 *
		 * However, `$wpdb->get_var()` also returns `null` when a query succeeds
		 * but produces no results — not only on failure.
		 *
		 * Therefore, this method checks `$wpdb->last_error` to determine
		 * whether an actual database error occurred.
		 *
		 * @link https://github.com/solvebeam/wpdbx/issues/1
		 */
		if ( null === $result && '' !== $this->wpdb->last_error ) {
			throw new \Exception(
				\sprintf(
					'Error get var %s: %s.',
					$query,
					$this->wpdb->last_error
				)
			);
		}

		return $result;
	}
}
