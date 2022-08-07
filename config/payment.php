<?php

return [
    /**
     * API Token Key (string)
     * Accepted Values:
     * Live Token: https://myfatoorah.readme.io/docs/live-token
     * Test Token: https://myfatoorah.readme.io/docs/test-token
     */
    'api_key' => env('PAYMENT_API_KEY', 'rLtt6JWvbUHDDhsZnfpAhpYk4dxYDQkbcPTyGaKp2TYqQgG7FGZ5Th_WD53Oq8Ebz6A53njUoo1w3pjU1D4vs_ZMqFiz_j0urb_BH9Oq9VZoKFoJEDAbRZepGcQanImyYrry7Kt6MnMdgfG5jn4HngWoRdKduNNyP4kzcp3mRv7x00ahkm9LAK7ZRieg7k1PDAnBIOG3EyVSJ5kK4WLMvYr7sCwHbHcu4A5WwelxYK0GMJy37bNAarSJDFQsJ2ZvJjvMDmfWwDVFEVe_5tOomfVNt6bOg9mexbGjMrnHBnKnZR1vQbBtQieDlQepzTZMuQrSuKn-t5XZM7V6fCW7oP-uXGX-sMOajeX65JOf6XVpk29DP6ro8WTAflCDANC193yof8-f5_EYY-3hXhJj7RBXmizDpneEQDSaSz5sFk0sV5qPcARJ9zGG73vuGFyenjPPmtDtXtpx35A-BVcOSBYVIWe9kndG3nclfefjKEuZ3m4jL9Gg1h2JBvmXSMYiZtp9MR5I6pvbvylU_PP5xJFSjVTIz7IQSjcVGO41npnwIxRXNRxFOdIUHn0tjQ-7LwvEcTXyPsHXcMD8WtgBh-wxR8aKX7WPSsT1O8d8reb2aR7K3rkV3K82K_0OgawImEpwSvp9MNKynEAJQS6ZHe_J_l77652xwPNxMRTMASk1ZsJL'),

    /**
     * Test Mode (boolean)
     * Accepted Values: true for the test mode, or false for the live mode
     */
    'test_mode' => env('PAYMENT_TEST_MODE', true),

    /**
     * Country ISO Code (string)
     * Accepted Values: KWT, SAU, ARE, QAT, BHR, OMN, JOD, or EGY.
     */
    'country_iso' => env('PAYMENT_COUNTRY_ISO', 'KWT'),

    /**
     * Display Currency Code (string)
     * Accepted Values: KWD, SAR, BHD, AED, QAR, OMR, JOD, or EGP.
     */
    'display_currency' => env('PAYMENT_DISPLAY_CURRENCY', 'KWD'),

    /**
     * Minimum Payment Threshold (Integer)
     * This is the minimum invoice amount that can be issued.
     */
    'payment_threshold' => env('PAYMENT_THRESHOLD', 40),
];
