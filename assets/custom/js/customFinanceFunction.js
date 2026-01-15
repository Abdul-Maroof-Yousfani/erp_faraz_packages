		var baseUrl = $('#baseUrl').val();

		function viewRangeWiseDataFilter() {

			var fromDate = $('#fromDate').val();
			var toDate = $('#toDate').val();
            // Parse the entries
			var startDate = Date.parse(fromDate);
			var endDate = Date.parse(toDate);
			// Make sure they are valid
			if (isNaN(startDate)) {
				alert("The start date provided is not valid, please enter a valid date.");
				return false;
			}
			if (isNaN(endDate)) {
				alert("The end date provided is not valid, please enter a valid date.");
				return false;
			}
			// Check the date range, 86400000 is the number of milliseconds in one day
			var difference = (endDate - startDate) / (86400000 * 7);
			if (difference < 0) {
				alert("The start date must come before the end date.");
				return false;
			}
			filterVoucherList();
		}
		function filterVoucherList(){

			var fromDate = $('#fromDate').val();
			var toDate = $('#toDate').val();
			var functionName = $('#functionName').val();
			var tbodyId = $('#tbodyId').val();
			var m = $('#m').val();
            var url = ''+baseUrl+'/'+functionName+'';
            //var filterType = $('#filterType').val();
            var selectVoucherStatus = $('#selectVoucherStatus').val();
            var selectAccountHead = $('#selectAccountHeadTwo').val();
            var selectAccountHeadId = $('#selectAccountHeadId').val();
            $('#'+tbodyId+'').html('<tr><td colspan="15"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');
            //if(filterType == 1){
                //$.getJSON(url, {fromDate:fromDate,toDate:toDate,m:m,selectVoucherStatus:selectVoucherStatus,selectAccountHead:selectAccountHead,selectAccountHeadId:selectAccountHeadId}, function (result) {
                    //$.each(result, function (i, field) {
                        //$('#' + tbodyId + '').html('' + field + '');
                    //});
                //})
            //}else{
				$.ajax({
					url: ''+baseUrl+'/'+functionName+'',
					method:'GET',
					data:{fromDate:fromDate,toDate:toDate,m:m,selectVoucherStatus:selectVoucherStatus,selectAccountHead:selectAccountHead,selectAccountHeadId:selectAccountHeadId},
					error: function(){
						alert('error');
					},
					success: function(response){

						var result=response.split('*');

						$('#'+tbodyId+'').html(result[0]);
						$('#h3').html(result[1]);

					}
				});
            //}
		}
		filterVoucherList();

		function deleteCompanyFinanceTwoTableRecords(m,voucherStatus,rowStatus,columnValue,columnOne,columnTwo,columnThree,tableOne,tableTwo){
			$.ajax({
				url: ''+baseUrl+'/fd/deleteCompanyFinanceTwoTableRecords',
				type: "GET",
				data: {m:m,voucherStatus:voucherStatus,rowStatus:rowStatus,columnValue:columnValue,columnOne:columnOne,columnTwo:columnTwo,columnThree:columnThree,tableOne:tableOne,tableTwo:tableTwo},
				success:function(data) {
					filterVoucherList();
				}
      		});
		}

		function repostCompanyFinanceTwoTableRecords(m,voucherStatus,rowStatus,columnValue,columnOne,columnTwo,columnThree,tableOne,tableTwo){
			$.ajax({
				url: ''+baseUrl+'/fd/repostCompanyFinanceTwoTableRecords',
				type: "GET",
				data: {m:m,voucherStatus:voucherStatus,rowStatus:rowStatus,columnValue:columnValue,columnOne:columnOne,columnTwo:columnTwo,columnThree:columnThree,tableOne:tableOne,tableTwo:tableTwo},
				success:function(data) {
					filterVoucherList();
				}
      		});
		}

		function approveCompanyFinanceTwoTableRecords(m,voucherStatus,rowStatus,columnValue,columnOne,columnTwo,columnThree,tableOne,tableTwo){
			$.ajax({
				url: ''+baseUrl+'/fd/approveCompanyFinanceTwoTableRecords',
				type: "GET",
				data: {m:m,voucherStatus:voucherStatus,rowStatus:rowStatus,columnValue:columnValue,columnOne:columnOne,columnTwo:columnTwo,columnThree:columnThree,tableOne:tableOne,tableTwo:tableTwo},
				success:function(data) {
					filterVoucherList();
				}
      		});
		}

		function deleteCompanyFinanceThreeTableRecords(m,voucherStatus,rowStatus,columnValue,columnOne,columnTwo,columnThree,tableOne,tableTwo,tableThree){
			$.ajax({
				url: ''+baseUrl+'/fd/deleteCompanyFinanceThreeTableRecords',
				type: "GET",
				data: {m:m,voucherStatus:voucherStatus,rowStatus:rowStatus,columnValue:columnValue,columnOne:columnOne,columnTwo:columnTwo,columnThree:columnThree,tableOne:tableOne,tableTwo:tableTwo,tableThree:tableThree},
				success:function(data) {
					filterVoucherList();
				}
      		});
		}

		function repostCompanyFinanceThreeTableRecords(m,voucherStatus,rowStatus,columnValue,columnOne,columnTwo,columnThree,tableOne,tableTwo,tableThree){
			$.ajax({
				url: ''+baseUrl+'/fd/repostCompanyFinanceThreeTableRecords',
				type: "GET",
				data: {m:m,voucherStatus:voucherStatus,rowStatus:rowStatus,columnValue:columnValue,columnOne:columnOne,columnTwo:columnTwo,columnThree:columnThree,tableOne:tableOne,tableTwo:tableTwo,tableThree:tableThree},
				success:function(data) {
					filterVoucherList();
				}
      		});
		}

        $("#selectAccountHeadTwo").change(function() {
			var selectAccountHeadTwo = $('#selectAccountHead option[value="' + $('#selectAccountHeadTwo').val() + '"]').data('id');
			$('#selectAccountHeadId').val(selectAccountHeadTwo);
        }).change();