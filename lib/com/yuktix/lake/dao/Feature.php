<?php

namespace com\yuktix\lake\dao {

    use \com\indigloo\Configuration as Config ;
    use \com\indigloo\Logger as Logger ;
    use \com\indigloo\mysql as MySQL;
    use \com\indigloo\mysql\PDOWrapper;
    use \com\indigloo\exception\UIException as UIException;

    use \com\yuktix\lake\api\Response as Response ;
    use \com\yuktix\lake\mysql\Feature as FeatureDB ;
    use \com\yuktix\lake\dao\File as FileDao ;
    use \com\yuktix\lake\Constants as LakeConstants ;
    
    class Feature {

        static function uploadData($postData) {

            // input check 
            if(empty($postData->fileId)) {
                $xmsg = "no fileId found in  POST data!";
                Response::raiseBadInputError($xmsg) ;
            }

            if(empty($postData->lakeId)) {
                $xmsg = "no lake_id found in  POST data!";
                Response::raiseBadInputError($xmsg) ;
            }

            // @todo 
            // special treatment for LAKE_LEVEL 
            if(!empty($postData->ioCode) && ($postData->ioCode != 3)) {
                if(empty($postData->featureId)) {
                    $xmsg = "no feature_id found in  POST data!";
                    Response::raiseBadInputError($xmsg) ;
                }
            }

            $dbh = NULL ;
            try {

                $dbh = PDOWrapper::getHandle();
                $dbh->beginTransaction();
                
                // lake Id
                // feature Id
                // fileId
                // calibrationFileId 
                FeatureDB::storeData(
                    $dbh,
                    $postData->lakeId,
                    $postData->featureId,
                    $postData->ioCode,
                    $postData->fileId,
                    $postData->calibrationFileId 
                );

                $dbh->commit();
                $dbh = null;

            } catch (\Exception $ex) {
                $dbh->rollBack();
                $dbh = null;
                throw $ex ;
            }
          
        }
    }

}

?>
