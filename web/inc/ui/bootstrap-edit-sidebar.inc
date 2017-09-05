
<div class="col-md-3" id="sidebar-wrapper" style="background-color:black">

    <ul class="sidebar-nav">

        <li class="sidebar-brand">
            <a href="#">
                <h2 ng-bind="lakeObj.name">  </h2>
            </a>
        </li>
        
        <li ng-class="{active : display.lakeEditMenu.general} ">
                    <a href="/admin/lake/edit.php?lake_id=<?php echo $lakeId; ?>"> 
                        <span>General information</span> 
                    </a>
                    
                </li>
                
                <li ng-class="{active : display.lakeEditMenu.waterBalance} ">
                    <a href="/admin/lake/wb/upload.php?lake_id=<?php echo $lakeId; ?>"> 
                        <span>Water Balance</span>
                    </a>
                </li>

                <li ng-class="{active : display.lakeEditMenu.zone} ">
                    <a href="/admin/lake/zone.php?lake_id=<?php echo $lakeId; ?>"> 
                        <span>Zones/Boundaries</span> 
                    </a>
                   
                </li>

                 <li ng-class="{active : display.lakeEditMenu.feature} ">
                    <a href="/admin/lake/feature/list.php?lake_id=<?php echo $lakeId; ?>"> 
                        <span>Inlet/Outlets</span> 
                    </a>
                </li>

                <li ng-class="{active : display.lakeEditMenu.bathymetry} ">
                    <a href="/admin/lake/bathymetry.php?lake_id=<?php echo $lakeId; ?>"> 
                        <span>Bathymetry</span> 
                    </a>
                </li>

                <li ng-class="{active : display.lakeEditMenu.stageVolume} ">
                    <a href="/admin/lake/stage-volume.php?lake_id=<?php echo $lakeId; ?>" ng-click="goto_stage_volume_page(lakeId)"> 
                        <span>Stage volume</span> 
                    </a>
                </li>

                 <li ng-class="{active : display.lakeEditMenu.stageArea} ">
                    <a href="/admin/lake/stage-area.php?lake_id=<?php echo $lakeId; ?>"> 
                        <span>Stage area</span> 
                    </a>
                </li>

                <li ng-class="{active : display.lakeEditMenu.evaporation} ">
                    <a href="/admin/lake/evaporation.php?lake_id=<?php echo $lakeId; ?>"> 
                        <span>Evaporation</span> 
                    </a>
                </li>


        </ul>
    </div> <!-- edit:sidebar -->


