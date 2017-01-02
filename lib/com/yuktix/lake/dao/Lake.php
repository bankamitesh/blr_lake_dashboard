<?php

namespace com\yuktix\lake\dao {

    use \com\indigloo\Configuration as Config ;
    use \com\indigloo\Logger as Logger ;
    use \com\indigloo\mysql as MySQL;
    use \com\indigloo\mysql\PDOWrapper;

    use \com\yuktix\lake\api\Response as Response ;
    use \com\yuktix\lake\mysql\Lake as LakeDB ;

    class Lake {

        static function storeImages($lakeId, $fileIds) {

            if(empty($lakeId)) {
                $xmsg = "required parameter lakeId is missing";
                Response::raiseBadInputError($xmsg) ;
            }

            if(empty($fileIds)) {
                $xmsg = "required  fileId array is empty!";
                Response::raiseBadInputError($xmsg) ;
            }

            $dbh = NULL ;
            try {

                $dbh = PDOWrapper::getHandle();
                $dbh->beginTransaction();
                foreach($fileIds as $fileId) {
                    LakeDB::storeImage($dbh,$lakeId, $fileId);
                }
                
                $dbh->commit();
                $dbh = null;

            } catch (\Exception $ex) {
                $dbh->rollBack();
                $dbh = null;
                throw $ex ;
            }

        }

        static function getImages($lakeId) {

            if(empty($lakeId)) {
                $xmsg = "Required parameter lakeId is missing";
                Response::raiseBadInputError($xmsg) ;
            }

            $rows = LakeDB::getImages($lakeId);
            $result = array() ;

            foreach($rows as $row) {

                $image = new \stdClass ;
                $image->name = $row["file_name"];
                $image->mime = $row["mime"];
                $image->size = $row["file_size"];
                $image->tsUnix = $row["unix_ts"];
                $image->fileId = $row["file_id"];
                array_push($result, $image);

            }

            return $result ;

        }

        static function setWallpaper($lakeId, $fileId) {

            if(empty($lakeId)) {
                $xmsg = "required parameter lakeId is missing";
                Response::raiseBadInputError($xmsg) ;
            }

            if(empty($fileId)) {
                $xmsg = "required  fileId image file id is missing!";
                Response::raiseBadInputError($xmsg) ;
            }

            $dbh = NULL ;

            try {
                
                $dbh = PDOWrapper::getHandle();
                $dbh->beginTransaction();
                LakeDB::setWallpaper($dbh,$lakeId, $fileId);
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