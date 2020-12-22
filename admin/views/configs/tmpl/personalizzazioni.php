<?php
/**
 * Created by IntelliJ IDEA.
 * User: Francesca
 * Date: 09/11/2020
 * Time: 10:37
 */
?>

<!-- Genera coupon -->
<div class="row-fluid">

    <h4>Genera coupon</h4>

    <div class="row-fluid">
        <?php echo $this->form->renderField('genera_coupon_label_partita_iva'); ?>
    </div>

    <div class="row-fluid">
        <?php echo $this->form->renderField('genera_coupon_label_partita_iva_missing'); ?>
    </div>

    <div class="row-fluid">
        <?php echo $this->form->renderField('genera_coupon_label_ragione_sociale'); ?>
    </div>

    <div class="row-fluid">
        <?php echo $this->form->renderField('genera_coupon_label_email_tutor_aziendale'); ?>
    </div>

    <div class="row-fluid">
        <?php echo $this->form->renderField('genera_coupon_visualizza_venditore'); ?>
    </div>

    <div class="row-fluid">
        <?php echo $this->form->renderField('genera_coupon_visualizza_ateco'); ?>
    </div>

    <div class="row-fluid">
        <?php echo $this->form->renderField('genera_coupon_visualizza_stampa_tracciato'); ?>
    </div>

</div>

<!-- Summary report -->
<div class="row-fluid">

    <h4>Summary report</h4>

    <div class="row-fluid">
        <?php echo $this->form->renderField('summary_report_nascondi_colonne'); ?>
    </div>

    <h5>Dizionario colonne</h5>

    <div class="row-fluid">

        <table class="table table-striped">

            <tr>
                <td>
                    <?php echo $this->form->renderField('summary_report_label_coupon'); ?>
                </td>
                <td>
                    <?php echo $this->form->renderField('summary_report_label_nome'); ?>
                </td>
                <td>
                    <?php echo $this->form->renderField('summary_report_label_cognome'); ?>
                </td>
            </tr>

            <tr>
                <td>
                    <?php echo $this->form->renderField('summary_report_label_cf'); ?>
                </td>
                <td>
                    <?php echo $this->form->renderField('summary_report_label_corso'); ?>
                </td>
                <td>
                    <?php echo $this->form->renderField('summary_report_label_azienda'); ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo $this->form->renderField('summary_report_label_stato'); ?>
                </td>
                <td>
                    <?php echo $this->form->renderField('summary_report_label_attestato'); ?>
                </td>
                <td>
                    <?php echo $this->form->renderField('summary_report_label_venditore'); ?>
                </td>
            </tr>

        </table>

    </div>

    <div class="row-fluid">

    </div>


</div>
