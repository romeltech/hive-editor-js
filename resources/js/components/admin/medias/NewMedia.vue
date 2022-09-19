<template>
  <div>
    <media-form
      :posttype="'post'"
      :redirectedit="'EditMedia'"
      :newurl="'/d/admin/media/save'"
      :headertitle="'News & Articles'"
    ></media-form>
  </div>
</template>

<script>
import mediaForm from "./MediaForm";
export default {
  components: { mediaForm },
  data() {
    return {
      //   pagetitle: 'Create New' ,
    };
  },
};
</script>

<style>
</style>
