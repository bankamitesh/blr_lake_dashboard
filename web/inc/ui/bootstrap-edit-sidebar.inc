
<head>
    <style>
        .bs-docs-sidebar li {
            cursor : pointer ;
        }

        .bs-docs-sidebar .nav>li>a {

            display: block;
            padding: 4px 20px;
            font-size: 13px;
            font-weight: 500;
            color: #767676;

        }

        .bs-docs-sidebar .nav .nav>li>a {
            padding-top: 1px;
            padding-bottom: 1px;
            padding-left: 30px;
            font-size: 12px;
            font-weight: 400;
        }

        bs-docs-sidebar .nav>.active:focus>a,
        .bs-docs-sidebar .nav>.active:hover>a,
        .bs-docs-sidebar .nav>.active>a {

            padding-left: 18px;
            font-weight: 700;
            color: #563d7c;
            background-color: transparent;
            border-left: 2px solid #563d7c;

        }

    </style>

</head>


<div class="col-md-2"></div>
<div class="col-md-3" >
    <nav class="bs-docs-sidebar">

        <ul  class="nav">

            <li class="sidebar-brand">
            
                <h3 ng-bind="lakeObj.name"></h3>
            
            </li>
            </br>
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
    </nav>
</div> <!-- edit:sidebar -->


