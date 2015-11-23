<!DOCTYPE html>
<html>
<head>
	<title>Generate MSmvc</title>
	<meta charset="UTF-8">
	<meta name="author" content="Maurice Schurink">
	<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ==" crossorigin="anonymous">
</head>
<body>
<div class="container">
	<div class="row">
		<div class="title col-md-12"><h2>Generate</h2></div>
		<div class="content col-md-12">
			<form method="post" id="form1" action="#">
				<p>Please make a choice what you wish to generate</p>

				<div class="form-group">
					<label class="checkbox-inline">
						<input type="checkbox" name="controller" class="generateCheckbox" value="true" checked> Controller
					</label>
					<label class="checkbox-inline">
						<input type="checkbox" name="model" class="generateCheckbox" value="true" checked> Model
					</label>
					<label class="checkbox-inline">
						<input type="checkbox" name="database" class="generateCheckbox" value="true"> Generate based on a database table
					</label>
				</div>
				<div class="form-group" id="normalSelectionHolder">
					<label for="fileNameInputField">Name</label>
					<input type="text" class="form-control" name="name" id="fileNameInputField" placeholder="Name">
				</div>
				<div class="form-group" id="databaseSelectionHolder" style="display: none;">
					<div class="row">
						<div class="col-md-6">
							<label for="databaseConnectionSelector">Database connection set</label>
							<select id="databaseConnectionSelector" class="form-control" name="databaseConnectionReference" form="form1">
								<option selected value="null" disabled>Please select a connection set</option>
								<?php foreach($connectionSets as $name => $connectionSet) {
									echo '<option value="' . $name . '">' . $name . '</option>';
								}
								?>
							</select>
						</div>
						<div class="col-md-6">
							<label for="tableDatabaseSelector">Database table(s)</label>
							<select id="tableDatabaseSelector" multiple class="form-control" disabled name="databaseTableCollection" form="form1">
								<option disabled value="null">Please select a connection set</option>
							</select>
						</div>
					</div>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" name="overwrite" value="true">I wish to overwrite any existing files with the same name
					</label>
				</div>
				<button type="submit" class="btn btn-default">Generate</button>
			</form>
		</div>
	</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js" integrity="sha512-K1qjQ+NcF2TYO/eI3M6v8EiNYZfA95pQumfvcVrTHtwQVDG+aHRqLi/ETn2uB+1JqwYqVG3LIvdm9lj6imS/pQ==" crossorigin="anonymous"></script>
<script src="../../public/scripts/MSmain.js"></script>
</body>
</html>