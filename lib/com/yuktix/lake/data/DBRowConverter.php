<?php

namespace com\yuktix\lake\data {

    use \com\indigloo\Configuration as Config ;
    use \com\indigloo\Logger as Logger ;
    
    class DBRowConverter {

        static function convertLakeRow($row) {

            $lake = new \stdClass ;
            if(empty($row)) {
                return $lake ;
            }

            $lake->name = $row["name"];
            $lake->about = $row["about"];
            $lake->lat = $row["lat"] ;
            $lake->lon = $row["lon"] ;

            $lake->address = $row["address"] ;
            $lake->maxArea = $row["max_area"] ;
            $lake->maxVolume = $row["max_volume"] ;
            $lake->rechargeRate = $row["recharge_rate"] ;

            $lake->typeCode = $row["type_code"];
            $lake->agencyCode = $row["agency_code"];
            $lake->usageCode = [] ;
            $dbUsageCodes = json_decode($row["usage_code"]);
            foreach ($dbUsageCodes as $dbUsageCode) {
                array_push($lake->usageCode, intval($dbUsageCode));
            }

            $lake->id = $row["id"];

            return $lake ;

        }

    }

}
