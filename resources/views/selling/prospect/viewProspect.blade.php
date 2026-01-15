

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="panel">
            <div class="panel-body">
                <div class="vctn">
                    <h2>Contact</h2>
                    <ul class="vclist">
                        <li>
                            <div class="vcbox">
                                <p>Full Name</p>
                                <h3>{{ $prospect->contact->first_name.' '.$prospect->contact->last_name }}</h3>
                            </div>
                        </li>
                        <li>
                            <div class="vcbox">
                                <p>Cell </p>
                                <h3>{{ $prospect->contact->cell }}</h3>
                            </div>
                        </li>
                        <li>
                            <div class="vcbox">
                                <p>Website</p>
                                <h3>{{ $prospect->contact->website }}</h3>
                            </div>
                        </li>
                        <li>
                            <div class="vcbox">
                                <p>Gender </p>
                                <h3>{{ $prospect->contact->gender == 1 ? 'Male' : 'Female' }}</h3>
                            </div>
                        </li>
                        <li>
                            <div class="vcbox">
                                <p>Phone</p>
                                <h3>{{ $prospect->contact->phone }}</h3>
                            </div>
                        </li>
                        <li>
                            <div class="vcbox">
                                <p>Job Title</p>
                                <h3>{{ $prospect->contact->personal_title  }}</h3>
                            </div>
                        </li>
                        <li>
                            <div class="vcbox">
                                <p>Personal Title</p>
                                <h3>{{ $prospect->contact->personal_title  }}</h3>
                            </div>
                        </li>
                        <li>
                            <div class="vcbox">
                                <p>Email</p>
                                <h3>{{ $prospect->contact->email }}</h3>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="vctn">
                    <h2>Company Details</h2>
                    <ul class="vclist">
                        <li>
                            <div class="vcbox">
                                <p>Company Name</p>
                                <h3>{{ $prospect->company_name }}</h3>
                            </div>
                        </li>
                        <li>
                            <div class="vcbox">
                                <p>Customer Group </p>
                                <h3>{{ $prospect->companyGroup->name }}</h3>
                            </div>
                        </li>
                        <li>
                            <div class="vcbox">
                                <p>Company Location</p>
                                <h3>{{ $prospect->companyLocation->name }}</h3>
                            </div>
                        </li>
                        <li>
                            <div class="vcbox">
                                <p>Company Address </p>
                                <h3>{{ $prospect->company_address }}</h3>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>