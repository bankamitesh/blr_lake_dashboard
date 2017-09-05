
<div class="col-md-3" >

    <ul  id="sidebar" class="well nav nav-stacked" style=" padding:50px">

        <li class="sidebar-brand">
            <center>
                <h3 ng-bind="lakeObj.name"></h3>
            </center>
        </li>
        
        <li ng-class="{'active' : display.lakeEditMenu.general} ">
                    <a href="/admin/lake/edit.php?lake_id=<?php echo $lakeId; ?>"> 
                        General information
                    </a>
                    
                </li>
                
                <li ng-class="{'active' : display.lakeEditMenu.waterBalance} ">
                    <a href="/admin/lake/wb/upload.php?lake_id=<?php echo $lakeId; ?>"> 
                        Water Balance
                    </a>
                </li>

                <li ng-class="{'active' : display.lakeEditMenu.zone} ">
                    <a href="/admin/lake/zone.php?lake_id=<?php echo $lakeId; ?>"> 
                        Zones/Boundaries 
                    </a>
                   
                </li>

                 <li ng-class="{'active' : display.lakeEditMenu.feature} ">
                    <a href="/admin/lake/feature/list.php?lake_id=<?php echo $lakeId; ?>"> 
                        Inlet/Outlets 
                    </a>
                </li>

                <li ng-class="{'active' : display.lakeEditMenu.bathymetry} ">
                    <a href="/admin/lake/bathymetry.php?lake_id=<?php echo $lakeId; ?>"> 
                        Bathymetry 
                    </a>
                </li>

                <li ng-class="{'active' : display.lakeEditMenu.stageVolume} ">
                    <a href="/admin/lake/stage-volume.php?lake_id=<?php echo $lakeId; ?>" ng-click="goto_stage_volume_page(lakeId)"> 
                        Stage volume 
                    </a>
                </li>

                 <li ng-class="{'active' : display.lakeEditMenu.stageArea} ">
                    <a href="/admin/lake/stage-area.php?lake_id=<?php echo $lakeId; ?>"> 
                        Stage area 
                    </a>
                </li>

                <li ng-class="{'active' : display.lakeEditMenu.evaporation} ">
                    <a href="/admin/lake/evaporation.php?lake_id=<?php echo $lakeId; ?>"> 
                        Evaporation 
                    </a>
                </li>


        </ul>
    </div> <!-- edit:sidebar -->


