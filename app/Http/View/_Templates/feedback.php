<?php
// get the feedback (they are arrays, to make multiple positive/negative messages possible)
$feedback_positive = \System\Authentication\Session::get('feedback_positive');
$feedback_negative = \System\Authentication\Session::get('feedback_negative');

// echo out positive messages
if (!empty($feedback_positive)) {
    if(is_array($feedback_positive)) {
        foreach ($feedback_positive as $feedback) {
            echo '<div class="alert oaerror success alert-dismissible" role="alert">
<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
' . $feedback . '
</div>';
        }
    } else {
        echo '<div class="alert oaerror success alert-dismissible" role="alert">
<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
' . $feedback_positive . '
</div>';
    }
    \System\Authentication\Session::remove('','feedback_positive');
}

// echo out negative messages
if (!empty($feedback_negative)) {
    if(is_array($feedback_negative)) {
        foreach ($feedback_negative as $feedback) {
            echo '<div class="alert oaerror danger alert-dismissible" role="alert">
<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
' . $feedback . '
</div>';
        }
    } else {
        echo '<div class="alert oaerror danger alert-dismissible" role="alert">
<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
' . $feedback_negative . '
</div>';
    }
        \System\Authentication\Session::remove('','feedback_negative');
}
