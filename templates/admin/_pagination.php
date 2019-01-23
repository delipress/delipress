<?php defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' ); ?>

<div class="delipress__pagination">

    <?php
    $currentUrl = delipress_full_url($_SERVER);

    $nbPages = 1;
    ?>

    <?php
    if($paged != 1):
        $args =  array();
        if(isset($pagedType)){
            $args["paged-" . $pagedType] = $paged-1; 
        }
        else{
            $args["paged"] = $paged-1;
        }

        $previousPage = add_query_arg(
            $args, $currentUrl
        );
    ?>
    <a class="delipress__pagination__item" href="<?php echo esc_url($previousPage); ?>">
        <span class="dashicons dashicons-arrow-left-alt2"></span>
    </a>
    <?php
    endif;

    if($countTotal != 0){
        $nbPages = ceil( $countTotal / $numberPerPage );
    }

    if($nbPages > 1){
        for ($i=1; $i <= $nbPages ; $i++) {
            $current = false;

            if($i == $paged){
                $current = true;
            }

            $nextPage = $paged;
            if($paged < $nbPages){
                $nextPage = $paged+1;
            }
            $prevPage = $paged;
            if($paged > 1){
                $prevPage = $paged-1;
            }

            if(!in_array($i, array(1, $prevPage, $nextPage, $paged, $countTotal, $nbPages))){
                continue;
            }

            if($current):
            ?>
                <span class="delipress__pagination__item delipress__pagination__item--is-active">
                    <?php echo $i ?>
                </span>
            <?php
            else:

                $args =  array();
                if(isset($pagedType)){
                    $args["paged-" . $pagedType] = $i; 
                }
                else{
                    $args["paged"] = $i;
                }
                
                $url = add_query_arg(
                    $args, $currentUrl
                );
                ?>
                    <a class="delipress__pagination__item" href="<?php echo esc_url($url) ?>">
                        <?php echo $i; ?>
                    </a>
                <?php
            endif;
        }
    }

    ?>

    <?php 

    if($paged != $nbPages && $nbPages > 1):
        $args =  array();
        if(isset($pagedType)){
            $args["paged-" . $pagedType] = $paged+1; 
        }
        else{
            $args["paged"] = $paged+1;
        }

        $nextPage = add_query_arg(
            $args, $currentUrl
        );
        ?>
        <a class="delipress__pagination__item" href="<?php echo esc_url($nextPage) ?>">
            <span class="dashicons dashicons-arrow-right-alt2"></span>
        </a>
    <?php endif; ?>
</div>
