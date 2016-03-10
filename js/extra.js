/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

 function confirm(heading, question, cancelButtonTxt, okButtonTxt, callback) {

    var confirmModal = 
      $('<div class="modal hide fade">' +    
          '<div class="modal-header">' +
            '<a class="close" data-dismiss="modal" >&times;</a>' +
            '<h3>' + heading +'</h3>' +
          '</div>' +

          '<div class="modal-body">' +
            '<p>' + question + '</p>' +
          '</div>' +

          '<div class="modal-footer">' +
            '<a href="#" class="btn" data-dismiss="modal">' + 
              cancelButtonTxt + 
            '</a>' +
            '<a href="#" id="okButton" class="btn btn-primary">' + 
              okButtonTxt + 
            '</a>' +
          '</div>' +
        '</div>');

    confirmModal.find('#okButton').click(function(event) {
      callback();
      confirmModal.modal('hide');
    });

    confirmModal.modal('show');     
  };

  // ---------------------------------------------------------- Confirm Put To Use

  $("i#deleteTransaction").live("click", function(event) {
    // get txn id from current table row
    var id = $(this).data('id');

    var heading = 'Confirm Transaction Delete';
    var question = 'Please confirm that you wish to delete transaction ' + id + '.';
    var cancelButtonTxt = 'Cancel';
    var okButtonTxt = 'Confirm';

    var callback = function() {
      alert('delete confirmed ' + id);
    };

    confirm(heading, question, cancelButtonTxt, okButtonTxt, callback);

  });
