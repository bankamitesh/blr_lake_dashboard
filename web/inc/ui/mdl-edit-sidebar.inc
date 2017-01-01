<div class="mdl-cell   mdl-cell--3-col mdl-shadow--2dp lake-edit-menu">
    
        <h5 ng-bind="lakeObj.name">  </h5>
        <ul class="mdl-list">
            
                <li class="mdl-list__item" ng-class="{active : display.lakeEditMenu.general} ">
                    <a href="/admin/lake/edit.php?lake_id=<?php echo $lakeId; ?>" class="mdl-components__link mdl-component"> 
                        <span class="mdl-list__item-primary-content">General information</span> 
                    </a>
                    
                </li>
                
                <li class="mdl-list__item" ng-class="{active : display.lakeEditMenu.waterBalance} ">
                    <a href="/admin/lake/wb/upload.php?lake_id=<?php echo $lakeId; ?>" class="mdl-components__link mdl-component"> 
                        <span class="mdl-list__item-primary-content">Water Balance</span>
                    </a>
                </li>

                <li class="mdl-list__item" ng-class="{active : display.lakeEditMenu.zone} ">
                    <a href="/admin/lake/zone.php?lake_id=<?php echo $lakeId; ?>" class="mdl-components__link mdl-component"> 
                        <span class="mdl-list__item-primary-content">Zones/Boundaries</span> 
                    </a>
                   
                </li>

                 <li class="mdl-list__item" ng-class="{active : display.lakeEditMenu.feature} ">
                    <a href="/admin/lake/feature/list.php?lake_id=<?php echo $lakeId; ?>" class="mdl-components__link mdl-component"> 
                        <span class="mdl-list__item-primary-content">Inlet/Outlets</span> 
                    </a>
                </li>

                <li class="mdl-list__item" ng-class="{active : display.lakeEditMenu.bathymetry} ">
                    <a href="/admin/lake/bathymetry.php?lake_id=<?php echo $lakeId; ?>" class="mdl-components__link mdl-component"> 
                        <span class="mdl-list__item-primary-content">Bathymetry</span> 
                    </a>
                </li>

                <li class="mdl-list__item" ng-class="{active : display.lakeEditMenu.stageVolume} ">
                    <a href="/admin/lake/stage-volume.php?lake_id=<?php echo $lakeId; ?>" ng-click="goto_stage_volume_page(lakeId)" class="mdl-components__link mdl-component"> 
                        <span class="mdl-list__item-primary-content">Stage volume</span> 
                    </a>
                </li>

                 <li class="mdl-list__item" ng-class="{active : display.lakeEditMenu.stageArea} ">
                    <a href="/admin/lake/stage-area.php?lake_id=<?php echo $lakeId; ?>" class="mdl-components__link mdl-component"> 
                        <span class="mdl-list__item-primary-content">Stage area</span> 
                    </a>
                </li>

                <li class="mdl-list__item" ng-class="{active : display.lakeEditMenu.evaporation} ">
                    <a href="/admin/lake/evaporation.php?lake_id=<?php echo $lakeId; ?>" class="mdl-components__link mdl-component"> 
                        <span class="mdl-list__item-primary-content">Evaporation</span> 
                    </a>
                </li>


        </ul>
    </div> <!-- edit:sidebar -->

