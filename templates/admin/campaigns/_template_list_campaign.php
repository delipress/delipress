<?php 

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' ); 

use Delipress\WordPress\Helpers\ProviderHelper; 

$provider = $this->optionServices->getProvider();
$providerKey = $this->optionServices->getProviderKey();

?>

<li class="delipress__list__item delipress__flex delipress__flex--center">
    <div class="delipress__list__item__col-box">
        <input type="checkbox" id="<?php echo $campaign->getId(); ?>" class="delipress__checkbox__input <?php echo $classList; ?>" name="<?php echo $nameCheckbox; ?>" value="<?php echo $campaign->getId(); ?>" />
        <label for="<?php echo $campaign->getId(); ?>" class="delipress__checkbox"></label>
    </div>
    <div class="delipress__f5">
        <?php switch($typeCampaign):
            case "draft":
                ?>
                <a href="<?php echo $this->campaignServices->getCreateUrlByNextStep(1, $campaign->getId(), true); ?>" class="delipress__list__item__title">
                    <?php echo $campaign->getTitle(); ?>
                </a>
                <?php
                break;
            default:
                if($campaign->getIsSend()): ?>
                    <?php if($providerKey != ProviderHelper::SENDGRID && $provider["is_connect"]): ?>
                        <a href="<?php echo $this->campaignServices->getCampaignStatistic($campaign->getId()); ?>" class="delipress__list__item__title">
                            <?php echo $campaign->getTitle(); ?>
                        </a>
                    <?php else: ?>
                        <?php echo $campaign->getTitle(); ?>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="<?php echo $this->campaignServices->getCreateUrlByNextStep(1, $campaign->getId(), true); ?>" class="delipress__list__item__title">
                        <?php echo $campaign->getTitle(); ?>
                    </a>
                <?php
                endif;
                break;
        endswitch; ?>

    </div>
    <div class="delipress__f4">
        <?php esc_html_e('Recipients', 'delipress'); ?> <br>
        <?php
            $list             = $campaign->getLists();
            $totalSubscribers = $list->countSubscribers();

            if($list->object != null):
        ?>
        <a href="<?php echo $this->listServices->getSinglePageListUrl($list->getId()); ?>" class="delipress__tag">
            <?php echo $list->getName(); ?>
            <?php if(!$campaign->getIsSend()): ?>
                (<?php echo $totalSubscribers; ?> <?php
                    echo _n(
                        "subscriber",
                        "subscribers",
                        ((int) $totalSubscribers == 0) ? 1 : $totalSubscribers,
                        "delipress"
                    );
                ?>)
            <?php endif; ?>
        </a>
        <?php else: ?>
            <span class="delipress__tag delipress__tag--empty"><?php esc_html_e('Not yet defined', 'delipress'); ?></span>
        <?php endif; ?>
    </div>
    <div class="delipress__list__item__standard delipress__f3">
        <?php esc_html_e('Created by', 'delipress'); ?>
        <a href="">
            <?php echo $campaign->getAuthor(); ?>
        </a>
        <br><?php esc_html_e('On', 'delipress'); ?>
        <strong>
            <?php echo $campaign->getCreatedAt(); ?>
        </strong>
        <br />
        <?php
        $send = $campaign->getSend();
        if($send === "later" || ( $send === "now" && $typeCampaign === "send") ){
            esc_html_e("Delivery date : ", "delipress");
            echo sprintf("<strong>%s</strong>", $campaign->getDateSend(true) );
        }
        ?>
    </div>
    <div class="delipress__list__item__col-actions delipress__f4">
        <?php if(!$campaign->getIsSend()): ?>
             <a href="<?php echo $this->campaignServices->getCreateUrlByNextStep(1,$campaign->getId(), true ); ?>" class="delipress__button delipress__button--soft">
                <?php esc_html_e('Edit campaign', 'delipress'); ?>
            </a>
        <?php else: ?>
            <?php if($providerKey != ProviderHelper::SENDGRID && $provider["is_connect"]): ?>
                <a href="<?php echo $this->campaignServices->getCampaignStatistic($campaign->getId()); ?>" class="delipress__button delipress__button--soft">
                    <?php esc_html_e('View statistics', 'delipress'); ?>
                </a>
            <?php endif; ?>
        <?php endif; ?>

        <nav class="delipress__more">
            <a href="" class="delipress__button delipress__button--soft">
                <span class="dashicons dashicons-arrow-down-alt2"></span>
            </a>
            <ul class="delipress__more__sub">
                <li>
                    <a
                        href="<?php echo $this->campaignServices->getDeleteCampaignUrl($campaign->getId()); ?>"
                        class="js-prevent-delete-action"
                        data-title="<?php echo sprintf(esc_html__("Delete %s", 'delipress'), esc_attr($campaign->getTitle())); ?>"
                        data-message="<?php esc_html_e("Do you really want to delete this campaign?", "delipress"); ?>"
                    >
                        <?php esc_html_e("Delete", "delipress"); ?>
                    </a>
                </li>

                <?php if($campaign->getIsSend()): ?>
                    <li>
                        <a target="_blank" href="<?php echo $this->campaignServices->getUrlViewCampaignOnline($campaign); ?>">
                            <?php esc_html_e("View campaign online", "delipress"); ?>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</li>
