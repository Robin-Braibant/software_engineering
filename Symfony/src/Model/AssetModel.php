<?php namespace App\Model;

use App\Domain\Asset;
use Symfony\Component\HttpClient\Exception\InvalidArgumentException;

class AssetModel extends ModelBase implements IAssetModel {
    /**
     * @param $name
     * @return Asset|array
     */
    public function getByAssetName($name){
        $asset=[];
        try{
            $connection = $this->connectionFactory->getConnection();
            $query = "select id, roomId, name 
                      from asset 
                      where name = ?";
            $statement = $connection->prepare($query);
            $statement->execute([$name]);
            $assetRow = $statement->fetch();
            if(empty($assetRow)){
                throw new InvalidArgumentException("The entered name is not a correct name");
            }
            $asset = $this->createAssetFromRow($assetRow);
        } catch (\PDOException $exception){
            print $exception;
        }
        return $asset;
    }

    private function createAssetFromRow($assetRow)
    {
        $id = $assetRow['id'];
        $roomId = $assetRow['roomId'];
        $name = $assetRow['name'];

        $asset = new Asset($roomId, $name);
        $asset->setId($id);
        return $asset;
    }
}

