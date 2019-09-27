<div class="modal" role="dialog" id="dispute_modal" aria-hidden="true">
    <div class="modal-table">
        <div class="modal-cell">
            <div class="modal-content">
                <form accept-charset="UTF-8" action="{{ url('disputes/create') }}" id="create_dispute_form" method="post" name="create_dispute_form">
                    <input name="_token" type="hidden" id="_token" value="{{ csrf_token() }}" />
                    <input type="hidden" id="dispute_reservation_id" name="dispute_reservation_id" value="" ng-model="dispute_reservation_data.id" >
                    <div class="panel-header">
                        <a href="#" class="modal-close" data-behavior="modal-close">
                        </a>
                        {{ trans('messages.disputes.create_a_dispute') }}
                    </div>
                    <div id="dipute_form_content" >
                        <div class="panel-body">
                            <div>
                                <p>
                                    <strong>
                                        {{ trans('messages.disputes.dispute_reason') }}
                                    </strong>
                                </p>
                                <input type="text" name="subject" id="dispute_subject" ng-model="dispute_reservation_data.subject" >
                                <p class="text-danger">@{{dispute_form_errors.subject[0]}}</p>
                            </div>
                            <div class="row-space-top-2">
                                <label for="dispute_description">
                                    {{ trans('messages.disputes.dispute_description') }}
                                </label>
                                <textarea id="description" name="dispute_description" rows="5" ng-model="dispute_reservation_data.description"></textarea>
                                <p class="text-danger">@{{dispute_form_errors.description[0]}}</p>
                            </div>
                            <div class="row-space-top-2">
                                <label class="">
                                    <strong>{{trans('messages.account.amount')}}
                                    </strong>
                                </label>                            
                                <div class="input-addon">
                                    <span class="input-prefix">@{{dispute_reservation_data.currency_symbol}} @{{dispute_reservation_data.currency_code}}</span>
                                    <input type="text" name="amount" class="input-stem input-large" ng-model="dispute_reservation_data.amount">
                                </div>
                                <p class="text-danger">@{{dispute_form_errors.amount[0]}}</p>
                            </div>
                            <div class="row-space-2">
                                <label>
                                    {{trans('messages.disputes.documents')}}
                                </label>
                                <input type="file" name="documents" multiple class="form-control" id="dispute_documents" file="dispute_reservation_data.documents" />
                                <p class="text-danger">@{{dispute_form_errors.documents[0]}}</p>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <input type="hidden" name="decision" value="decline">
                            <input class="btn btn-primary" id="cancel_submit" name="commit" type="button" ng-click="submit_create_dispute()" value="{{ trans('messages.disputes.create_a_dispute') }}">
                            <button class="btn" data-behavior="modal-close">
                                {{ trans('messages.home.close') }}
                            </button>
                        </div>
                    </div>
                </form>      
            </div>
        </div>
    </div>
</div>