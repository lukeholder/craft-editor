var spinneropts = {
  lines: 7, // The number of lines to draw
  length: 4, // The length of each line
  width: 4, // The line thickness
  radius: 1, // The radius of the inner circle
  corners: 1, // Corner roundness (0..1)
  rotate: 0, // The rotation offset
  direction: 1, // 1: clockwise, -1: counterclockwise
  color: '#0078CC', // #rgb or #rrggbb
  speed: 1, // Rounds per second
  trail: 73, // Afterglow percentage
  shadow: false, // Whether to render a shadow
  hwaccel: true, // Whether to use hardware acceleration
  className: 'spinner', // The CSS class to assign to the spinner
  zIndex: 2e9, // The z-index (defaults to 2000000000)
  top: 'auto', // Top position relative to parent in px
  left: 'auto' // Left position relative to parent in px
};
showSpinner = function () {
  var target = document.getElementById('spinner');
  window.spinner = new Spinner(spinneropts).spin(target);
};
stopSpinner = function () {
  window.spinner.stop();
};

$.pnotify.defaults.delay = 1000;

window.onbeforeunload = function (evt) {
  if (editor.getSession().dirty) {
    var message = 'Are you sure you want to leave, cause there are some unsaved changes?';
    if (typeof evt == 'undefined') {
      evt = window.event;
    }
    if (evt) {
      evt.returnValue = message;
    }
    return message;
  }
}

saveFile = function () {
  showSpinner();
  var contents = editor.getSession().getValue();

  var data = {
    "file_path": editorSession.file_path,
    "contents": contents
  };

  $.ajax("/actions/editor/edit/saveTemplate", {
    type: 'POST',
    data: data,
    success: function (r) {
      editor.getSession().dirty = false;
      $.pnotify({
        title: 'Saved',
        text: 'File Saved.',
        type: 'success'
      });
      stopSpinner();
    },
    error: function (r) {
      alert("error, did not save.");
      stopSpinner();
    }
  });

};

var editor = ace.edit("editor");
editor.setTheme("ace/theme/monokai");
editor.setFontSize(14);
editor.getSession().setMode("ace/mode/twig");

editor.getSession().on('change', function () {
  editor.getSession().dirty = true;
  $("#editor-save").addClass("")
});

editor.commands.addCommand({
  name: "save",
  bindKey: {
    win: "Ctrl-S",
    mac: "Command-S",
    sender: "editor|cli"
  },
  exec: function () {
    saveFile();
  }
});
function DecodeHtml(str) {
    return $('<div/>').html(str).text();
}

$(document).ready(function () {

  $(".template-open").click(function (e) {
    e.preventDefault();
    if (editor.getSession().dirty) {
      $.pnotify({
        title: 'Cannot Switch',
        text: 'File not saved. Please save or reset file before opening another',
        type: 'error'
      });
    } else {
      window.location.href = $(this).attr('href');
    }
  });

  $("#editor-save").click(function (e) {
    e.preventDefault();
    saveFile();
  });
  $("#editor-undoall").click(function (e) {
    e.preventDefault();
    if (confirm("This will reset the contents on your file to when you first begun editing. Are you sure?")) {
      editor.getSession().setValue(DecodeHtml($('#editor-cache').html()));
      editor.getSession().dirty = true;
    }
  });
});
