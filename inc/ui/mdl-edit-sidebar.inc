<div class="mdl-cell docs-text-styling mdl-shadow--4dp mdl-cell--3-col">

        <ul class="lake-edit-links mdl-list ">
            
                <li class="mdl-list__item">
                    <a href="/admin/view/lake/edit.php?lake_id=<?php echo $lakeId; ?>" class="mdl-components__link mdl-component"> 
                        <span class="mdl-list__item-primary-content">General information</span> 
                    </a>
                </li>
                
                <li class="mdl-list__item">
                    <a href="/admin/view/lake/zone.php?lake_id=<?php echo $lakeId; ?>" class="mdl-components__link mdl-component"> 
                        <span class="mdl-list__item-primary-content">Zones/Boundaries</span> 
                    </a>
                </li>

                 <li class="mdl-list__item">
                    <a href="/admin/view/lake/feature/list.php?lake_id=<?php echo $lakeId; ?>" class="mdl-components__link mdl-component"> 
                        <span class="mdl-list__item-primary-content">Inlet/Outlets</span> 
                    </a>
                </li>

                <li class="mdl-list__item">
                    <a href="/admin/view/lake/bathymetry.php?lake_id=<?php echo $lakeId; ?>" class="mdl-components__link mdl-component"> 
                        <span class="mdl-list__item-primary-content">Bathymetry</span> 
                    </a>
                </li>

                <li class="mdl-list__item">
                    <a href="/admin/view/lake/stage-volume.php?lake_id=<?php echo $lakeId; ?>" ng-click="goto_stage_volume_page(lakeId)" class="mdl-components__link mdl-component"> 
                        <span class="mdl-list__item-primary-content">Stage volume</span> 
                    </a>
                </li>

                 <li class="mdl-list__item">
                    <a href="/admin/view/lake/stage-area.php?lake_id=<?php echo $lakeId; ?>" class="mdl-components__link mdl-component"> 
                        <span class="mdl-list__item-primary-content">Stage area</span> 
                    </a>
                </li>

                <li class="mdl-list__item">
                    <a href="/admin/view/lake/evaporation.php?lake_id=<?php echo $lakeId; ?>" class="mdl-components__link mdl-component"> 
                        <span class="mdl-list__item-primary-content">Evaporation data</span> 
                    </a>
                </li>


        </ul>
    </div> <!-- edit:sidebar -->


