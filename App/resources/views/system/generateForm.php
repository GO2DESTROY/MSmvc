<?php setLayout('system/layout'); ?>

<div class="row">
	<div class="title col-md-12"><h2>Generate</h2></div>
	<div class="row">
		<div class="col-md-6">
			<div class="content col-md-12">
				<form method="post" id="form1" action="/generate">
					<p>Type of generator</p>
					<div class="form-group">
						<label for="codeRadio" class="radio-inline">
							<input id="codeRadio" checked data-target="#codetab" type="radio" name="generatorType"
								   class="generatorRadio" value="code">Code
							first
						</label>

						<label class="radio-inline" for="databaseRadio">
							<input id="databaseRadio" data-target="#databasetab" type="radio" name="generatorType"
								   class="generatorRadio" value="database">Database
							first
						</label>
					</div>
					<p>Please make a choice what you wish to generate</p>
					<div class="form-group">
						<label class="checkbox-inline">
							<input type="checkbox" name="controller" class="generateCheckbox" value="true" checked>
							Controller
						</label>
						<label class="checkbox-inline">
							<input type="checkbox" name="model" class="generateCheckbox" value="true" checked> Model
						</label>
						<label class="checkbox-inline">
							<input type="checkbox" name="views" class="generateCheckbox" value="true" checked> View
						</label>
					</div>

				<div class="form-group" id="databaseSelectionHolder">
					<div class="tab-content">
						<div role="tabpanel" class="tab-pane fade" id="databasetab">
							<div class="form-group" id="databaseSelectionHolder">
								<div class="row">
									<div class="col-md-6">
										<label for="databaseConnectionSelector">Database connection set</label>
										<select id="databaseConnectionSelector" class="form-control"
												name="databaseConnectionReference" form="form1">
											<option selected value="null" disabled>Please select a connection set
											</option>
											<?php foreach ($connectionSets as $name => $connectionSet) {
												echo '<option value="' . $name . '">' . $name . '</option>';
											}
											?>
										</select>
									</div>
									<div class="col-md-6">
										<label for="tableDatabaseSelector">Database table(s)</label>
										<select id="tableDatabaseSelector" multiple class="form-control" disabled
												name="databaseTableCollection[]" form="form1">
											<option disabled value="null">Please select a connection set</option>
										</select>
									</div>
								</div>
							</div>
						</div>
						<div role="tabpanel" class="tab-pane fade" id="codetab">
							<div class="row">

								<div class="col-md-4">
									<div class="panel panel-default">
										<!-- Default panel contents -->
										<div class="panel-heading">Active Model</div>
										<ul class="list-group">
											<li class="list-group-item">model1
												<div class="pull-right"><label><input type="checkbox" name="update">
														<small>update</small>
													</label></div>
											</li>
											<li class="list-group-item">model2
												<div class="pull-right"><label><input type="checkbox" name="update">
														<small>update</small>
													</label></div>
											</li>
											<li class="list-group-item">Morbi leo risus
												<div class="pull-right"><label><input type="checkbox" name="update">
														<small>update</small>
													</label></div>
											</li>
											<li class="list-group-item">Porta ac consectetur ac
												<div class="pull-right"><label><input type="checkbox" name="update">
														<small>update</small>
													</label></div>
											</li>
											<li class="list-group-item">Vestibulum at eros
												<div class="pull-right"><label><input type="checkbox" name="update">
														<small>update</small>
													</label></div>
											</li>
										</ul>
									</div>
								</div>
								<div class="col-md-4">
									<div class="panel panel-default">
										<!-- Default panel contents -->
										<div class="panel-heading">Available Models</div>
										<ul class="list-group">
											<li class="list-group-item">model1</li>
											<li class="list-group-item">model2</li>
											<li class="list-group-item">Morbi leo risus</li>
											<li class="list-group-item">Porta ac consectetur ac</li>
											<li class="list-group-item">Vestibulum at eros</li>
										</ul>
									</div>
								</div>
								<div class="col-md-4">
									<div class="panel panel-default">
										<!-- Default panel contents -->
										<div class="panel-heading">Models to add</div>
										<ul class="list-group">
											<li class="list-group-item">model1</li>
											<li class="list-group-item">model2</li>
											<li class="list-group-item">Morbi leo risus</li>
											<li class="list-group-item">Porta ac consectetur ac</li>
											<li class="list-group-item">Vestibulum at eros</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-12">
							<div class="checkbox">
								<label>
									<input type="checkbox" name="routes" value="true">Create routes for the controllers
								</label>
							</div>
						</div>
					</div>
					<button type="submit" class="btn btn-default">Generate</button>
				</form>
			</div>
		</div>
		<div class="col-md-6">

			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">File Explorer</h3>
					</div>
					<div class="panel-body">

					</div>
				</div>
			</div>
		</div>
	</div>
</div>
