<template>
  <div>
    <media-form
      :posttype="'training'"
      :redirectedit="'EditTraining'"
      :newurl="'/d/admin/media/save'"
      :headertitle="'Trainings'"
    ></media-form>
  </div>
</template>

<script>
import mediaForm from "../medias/MediaForm.vue";
export default {
  components: { mediaForm },
  data() {
    return {
      //   pagetitle: 'Create New' ,
    };
  },
};
</script> 