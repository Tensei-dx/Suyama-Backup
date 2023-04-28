<?php

return [
    /**
     * Staysee API Access Token
     */
    'token' => env('STAYSEE_TOKEN', null),

    /**
     * There is a maximum range of 31 days when fetching the
     * reservation data from Staysee. We can use this variable to
     * set an offset of the start of the range relative to today.
     *
     * For example: If the 'fetch_offset' has a value of -7,
     * it will set the start of the range to the date 7 days ago
     * then set the end of the range 31 days after the start date.
     */
    'fetch_offset' => (int) env('STAYSEE_RESERVATION_FETCH_START_OFFSET', -7),

    /**
     * Set the number of days before a reservation is considered as
     * expired relative to the check out time of the reservation.
     *
     * For example: If the 'days_before_expire' has a value of 7,
     * If the check out time of a reservation is today,
     * and it is indeed checked out, it will be considered as expired
     * after 7 days.
     */
    'days_before_expire' => (int) env('STAYSEE_RESERVATION_DAYS_BEFORE_EXPIRE', 7),

    /**
     * It is possible that a single reservation from Staysee has multiple
     * rooms reserved, however, this reservations have the same reservation
     * information. In this case, we can't use the email from the
     * reservation as a username in iBMS because it will cause duplication.
     * The solution is to make a generated username.
     * We can set a prefix to the generated name.
     * We can also set a maximum length of the generated username excluding the prefix.
     */
    'username' => [
        'prefix' => env('STAYSEE_USERNAME_PREFIX', null),
        'generated_max_length' => (int) env('STAYSEE_USERNAME_GENERATED_MAX_LENGTH', 5)
    ],

    /**
     * Set the Hotel's default check in and check out time
     */
    'hotel_check_in_time' => env('HOTEL_CHECK_IN_TIME', '15:00:00'),
    'hotel_check_out_time' => env('HOTEL_CHECK_OUT_TIME', '10:00:00'),

    /**
     * The default number of minutes the guest can access their room before the check in time.
     */
    'buffer_check_in_time' => env('DEF_CHECK_IN_BUF', 30),

    /**
     * The default number of minutes the guest can access their room after the check out time.
     */
    'buffer_check_out_time' => env('DEF_CHECK_OUT_BUF', 15)
];
