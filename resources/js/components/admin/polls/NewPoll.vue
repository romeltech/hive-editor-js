<template>
  <div>
    <media-form
      :posttype="'poll'"
      :redirectedit="'EditPoll'"
      :newurl="'/d/admin/media/save'"
      :headertitle="'Polls'"
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