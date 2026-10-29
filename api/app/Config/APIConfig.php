<?php
namespace Config;
use CodeIgniter\Config\BaseConfig;

/**
 * Flight supplier credentials and endpoints.
 *
 * Nothing secret lives in this file: every credential is read from app/.env,
 * so each server talks to the supplier environment its own .env asks for.
 *
 *   flight.mode = Live   -> production supplier endpoints (live server)
 *   flight.mode = Test   -> test / pre-prod endpoints     (stage, local)
 *
 * A single supplier can be overridden with tbo.mode, travelport.mode or indigo.mode.
 * See the "FLIGHT SUPPLIER API" block in app/.env.example for every key.
 */
class APIConfig extends BaseConfig
{
      public $tbo_credential = [];

      public $travelport_credential = [];

      public $travelport_indigo_credential = [];

      protected $tbo_endpoints = [
            'Live' => [
                  'ClientId'         => 'tboprod',
                  'Authenticate_URL' => 'https://api.travelboutiqueonline.com/SharedAPI/SharedData.svc/rest/Authenticate',
                  'AirService_URL'   => 'https://tboapi.travelboutiqueonline.com/AirAPI_V10/AirService.svc',
                  'AirBooking_URL'   => 'https://booking.travelboutiqueonline.com/AirAPI_V10/AirService.svc',
            ],
            'Test' => [
                  'ClientId'         => 'ApiIntegrationNew',
                  'Authenticate_URL' => 'https://sharedapi.tektravels.com/SharedData.svc/rest/Authenticate',
                  'AirService_URL'   => 'http://api.tektravels.com/BookingEngineService_Air/AirService.svc',
                  'AirBooking_URL'   => 'http://api.tektravels.com/BookingEngineService_Air/AirService.svc',
            ],
      ];

      protected $travelport_endpoints = [
            'Live' => [
                  'AirService_URL'       => 'https://apac.universal-api.travelport.com/B2BGateway/connect/uAPI/AirService',
                  'UniversalService_URL' => 'https://apac.universal-api.travelport.com/B2BGateway/connect/uAPI/UniversalRecordService',
            ],
            'Test' => [
                  'AirService_URL'       => 'https://apac.universal-api.pp.travelport.com/B2BGateway/connect/uAPI/AirService',
                  'UniversalService_URL' => 'https://apac.universal-api.pp.travelport.com/B2BGateway/connect/uAPI/UniversalRecordService',
            ],
      ];

      public function __construct()
      {
            parent::__construct();

            $defaultMode = $this->mode(env('flight.mode'), 'Test');

            $tboMode = $this->mode(env('tbo.mode'), $defaultMode);
            $this->tbo_credential = array_merge($this->tbo_endpoints[$tboMode], [
                  'ClientId'  => env('tbo.clientId') ?: $this->tbo_endpoints[$tboMode]['ClientId'],
                  'UserName'  => (string) env('tbo.username'),
                  'Password'  => (string) env('tbo.password'),
                  'EndUserIp' => (string) env('tbo.endUserIp'),
                  'Mode'      => $tboMode,
            ]);

            $travelportMode = $this->mode(env('travelport.mode'), $defaultMode);
            $this->travelport_credential = array_merge($this->travelport_endpoints[$travelportMode], [
                  'UserName'      => (string) env('travelport.username'),
                  'Password'      => (string) env('travelport.password'),
                  'TargetBranch'  => (string) env('travelport.targetBranch'),
                  'PPC'           => (string) env('travelport.pcc'),
                  'Provider'      => env('travelport.provider') ?: '1G',
                  'AuthorizedBy'  => env('travelport.authorizedBy') ?: 'Liamtra',
                  'Provider_Type' => 'GDS',
                  'Mode'          => $travelportMode,
            ]);

            // IndiGo runs on the same uAPI account unless indigo.* keys are filled in.
            $indigoMode = $this->mode(env('indigo.mode'), $travelportMode);
            $this->travelport_indigo_credential = array_merge($this->travelport_endpoints[$indigoMode], [
                  'UserName'      => env('indigo.username') ?: $this->travelport_credential['UserName'],
                  'Password'      => env('indigo.password') ?: $this->travelport_credential['Password'],
                  'TargetBranch'  => env('indigo.targetBranch') ?: $this->travelport_credential['TargetBranch'],
                  'PPC'           => env('indigo.pcc') ?: $this->travelport_credential['PPC'],
                  'Provider'      => $this->travelport_credential['Provider'],
                  'AuthorizedBy'  => $this->travelport_credential['AuthorizedBy'],
                  'Provider_Type' => 'LCC',
                  'Mode'          => $indigoMode,
            ]);
      }

      /**
       * Anything other than "live" (any case) falls back to Test, so a typo
       * can never send stage traffic to the live supplier.
       */
      private function mode($value, string $default): string
      {
            if ($value === null || $value === false || trim((string) $value) === '') {
                  return $default;
            }
            return strtolower(trim((string) $value)) === 'live' ? 'Live' : 'Test';
      }
}
