<div>
    
    @if (Session::get('fail'))
        <div class="alert alert-danger">
            {{Session::get('fail')}}
            <button class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (Session::get('success'))
        <div class="alert alert-success">
            {{Session::get('success')}}
            <button class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

</div>