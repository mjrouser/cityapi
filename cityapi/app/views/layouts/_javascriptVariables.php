<script type="text/javascript">
    app = window.app || {};
    app.user = {};
    app.user.isGuest = <?php echo Yii::app()->user->isGuest ? 'true' : 'false' ?>;
    app.user.id = <?php echo Yii::app()->user->isGuest ? 'null' : Yii::app()->user->id ?>;
</script>