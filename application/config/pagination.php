<?php 

/*

   	<nav aria-label="Page navigation example">
      <ul class="pagination pagination-sm justify-content-end">

        <li class="page-item disabled">
          <a class="page-link" href="#" tabindex="-1">Previous</a>
        </li>

        <li class="page-item"><a class="page-link" href="#">1</a></li>
        <li class="page-item"><a class="page-link" href="#">2</a></li>
        <li class="page-item"><a class="page-link" href="#">3</a></li>

        <li class="page-item"><a class="page-link" href="#">Next
        </a>
        </li>

      </ul>
    </nav> 

*/

$config['full_tag_open'] = "<ul class='pagination pagination-sm justify-content-end'>";
$config['full_tag_close'] = "</ul>";

$config['first_link'] = false;
$config['last_link'] = false;

$config['first_tag_open'] = '<li class="page-item">';
$config['first_tag_close'] = '</li>';

$config['prev_link'] = 'Previous';
$config['prev_tag_open'] = "<li class='page-item '>";
$config['prev_tag_close'] = "</li>";

$config['next_link'] = 'Next';
$config['next_tag_open'] = "<li class='page-item'>";
$config['next_tag_close'] = "</li>";

$config['last_tag_open'] = '<li class="page-item">';
$config['last_tag_close'] = '</li>';

$config['cur_tag_open'] = '<li class="page-item active"><a class="page-link">';
$config['cur_tag_close'] = '</a></li>';

$config['num_tag_open'] = '<li class="page-item">';
$config['num_tag_close'] = '</li>';

$config['attributes'] = array('class' => 'page-link btnPage');