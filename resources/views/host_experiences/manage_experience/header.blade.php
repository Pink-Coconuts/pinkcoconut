<div class="col-sm-2 btn-sidebar">
  <i class="fa fa-bars side_menu_bar sidebarbar">
  </i>
</div>
<div class="col-sm-10 text-right">
  <ul class="list-inline list_style1">
    <li class="op_lw1 hide_sm save_loading" ng-init="changes_saved_text = '{{$host_experience->changes_saved}}'" > @{{changes_saved_text}}
    </li>
    <li>
      <a href="javascript:void(0)" class="rm_link @if(@$header_inverse) text-rausch @endif save_exit"> {{trans('experiences.manage.save_exit')}}
      </a>
    </li>
  </ul>        
</div>    