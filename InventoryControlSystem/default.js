// テキストボックスでエンターキーorタブキーを押したときのジャンプ処理
// 引数：
//        org：親フォーム（thisを入れておく)
//        nxt：ジャンプ先の部品名
// 戻り値：キー処理の有効・無効（falseでそのキーを押したときの本来の処理を無視する）
function EnterFocus(org, nxt)
{
	if(window.event.keyCode==13 || window.event.keyCode==9){
		org.form.elements[nxt].focus();
		return false;
	} else {
		return true;
	}
}


//テキストボックスでエンターキーorタブキーを押したときのジャンプ処理(ジャンプ先がラジオボタンの場合)
//引数：
//     org：親フォーム（thisを入れておく)
//     nxt：ジャンプ先の部品名
//戻り値：キー処理の有効・無効（falseでそのキーを押したときの本来の処理を無視する）
function EnterFocusR(org, nxt)
{
	if(window.event.keyCode==13 || window.event.keyCode==9){
		if (org.form.elements[nxt][0].checked == true) {
			org.form.elements[nxt][0].focus();
		} else {
			org.form.elements[nxt][1].focus();
		}
		return false;
	} else {
		return true;
	}
}

//テキストボックスでエンターキーorタブキーを押したときのジャンプ処理（日付補完付き）
//引数：
//     org：親フォーム（thisを入れておく)
//     nxt：ジャンプ先の部品名
//     cur：現在の部品名
//     def：空文字の場合には空文字を許可する
//戻り値：キー処理の有効・無効（falseでそのキーを押したときの本来の処理を無視する）
function EnterFocusDate(org, nxt, cur, def)
{
	var str;
	var dstr;
	var year;
	var month;
	var day;
	var sub1;
	var sub2;
	var sub3;
	var wrk_str;
	var dlm;
	var today = new Date();

	if(window.event.keyCode==13 || window.event.keyCode==9){
		str = org.form.elements[cur].value;

		if (str == "") {
			if (def != "") {
//				str = def;
//				alert("日付は必ず入力してください。");
//				org.form.elements[cur].focus();
				org.form.elements[nxt].focus();
				return false;
			}
		}

		dstr = str;


		if (dstr== "") {
		} else if (!str.match(/[^0-9]+/)) {
			if (str.length == 1) {
				year = today.getFullYear();
				month = today.getMonth() + 1;
				day = "0"+str;
			} else if (str.length == 2) {
				year = today.getFullYear();
				month = today.getMonth() + 1;
				day = str;
			} else if (str.length == 4) {
				year = today.getFullYear();
				month = str.substr(0, 2);
				day = str.substr(2, 2);
			} else if (str.length == 8) {
				year = str.substr(0, 4);
				month = str.substr(4, 2);
				day = str.substr(6, 2);
			}
		} else {
			wrk_str = str.replace(/[^0-9]/g, '-');

			dlm = wrk_str.indexOf("-");

			if (dlm < 0) {
			} else {
				sub1 = wrk_str.substr(0, dlm);
				wrk_str = wrk_str.substr(dlm + 1);
				dlm = wrk_str.indexOf("-");

				if (dlm < 0) {
					sub2 = wrk_str;
					sub3 = "";
				} else {
					sub2 = wrk_str.substr(0, dlm);
					sub3 = wrk_str.substr(dlm + 1);
				}
			}

			if (!sub1.match(/[^0-9]+/) && !sub2.match(/[^0-9]+/) && !sub3.match(/[^0-9]+/) && sub3 != "") {
				year = sub1;
				month = ("0"+sub2).slice(-2);
				day = ("0"+sub3).slice(-2);
			} else if (!sub1.match(/[^0-9]+/) && !sub2.match(/[^0-9]+/)) {
				year = today.getFullYear();
				month = ("0"+sub1).slice(-2);
				day = ("0"+sub2).slice(-2);
			}
		}

		dt = new Date(parseInt(year), parseInt(month) - 1, parseInt(day));

		if (dt.getFullYear()==year && dt.getMonth()==month-1 && dt.getDate()==day) {
			dstr = year + "-" + month + "-" + day;
			org.form.elements[cur].value = dstr;
			org.form.elements[nxt].focus();
		} else if (dstr == "") {
			org.form.elements[nxt].focus();
		} else {
//			alert("入力が正しくありません。");
//			org.form.elements[cur].focus();
			org.form.elements[nxt].focus();
		}

		return false;
	} else {
		return true;
	}
}

//テキストボックスでエンターキーorタブキーを押したときのジャンプ処理（日付補完付き）(ジャンプ先がラジオボタンの場合)
//引数：
//     org：親フォーム（thisを入れておく)
//     nxt：ジャンプ先の部品名
//     cur：現在の部品名
//     def：空文字の場合には空文字を許可する
//戻り値：キー処理の有効・無効（falseでそのキーを押したときの本来の処理を無視する）
function EnterFocusDateR(org, nxt, cur, def)
{
	var str;
	var dstr;
	var year;
	var month;
	var day;
	var sub1;
	var sub2;
	var sub3;
	var wrk_str;
	var dlm;
	var today = new Date();

	if(window.event.keyCode==13 || window.event.keyCode==9){
		str = org.form.elements[cur].value;

		if (str == "") {
			if (def != "") {
//				str = def;
//				alert("日付は必ず入力してください。");
//				org.form.elements[cur].focus();
				if (org.form.elements[nxt][0].checked == true) {
					org.form.elements[nxt][0].focus();
				} else {
					org.form.elements[nxt][1].focus();
				}
				return false;
			}
		}

		dstr = str;


		if (dstr== "") {
		} else if (!str.match(/[^0-9]+/)) {
			if (str.length == 1) {
				year = today.getFullYear();
				month = today.getMonth() + 1;
				day = "0"+str;
			} else if (str.length == 2) {
				year = today.getFullYear();
				month = today.getMonth() + 1;
				day = str;
			} else if (str.length == 4) {
				year = today.getFullYear();
				month = str.substr(0, 2);
				day = str.substr(2, 2);
			} else if (str.length == 8) {
				year = str.substr(0, 4);
				month = str.substr(4, 2);
				day = str.substr(6, 2);
			}
		} else {
			wrk_str = str.replace(/[^0-9]/g, '-');

			dlm = wrk_str.indexOf("-");

			if (dlm < 0) {
			} else {
				sub1 = wrk_str.substr(0, dlm);
				wrk_str = wrk_str.substr(dlm + 1);
				dlm = wrk_str.indexOf("-");

				if (dlm < 0) {
					sub2 = wrk_str;
					sub3 = "";
				} else {
					sub2 = wrk_str.substr(0, dlm);
					sub3 = wrk_str.substr(dlm + 1);
				}
			}

			if (!sub1.match(/[^0-9]+/) && !sub2.match(/[^0-9]+/) && !sub3.match(/[^0-9]+/) && sub3 != "") {
				year = sub1;
				month = ("0"+sub2).slice(-2);
				day = ("0"+sub3).slice(-2);
			} else if (!sub1.match(/[^0-9]+/) && !sub2.match(/[^0-9]+/)) {
				year = today.getFullYear();
				month = ("0"+sub1).slice(-2);
				day = ("0"+sub2).slice(-2);
			}
		}

		dt = new Date(parseInt(year), parseInt(month) - 1, parseInt(day));

		if (dt.getFullYear()==year && dt.getMonth()==month-1 && dt.getDate()==day) {
			dstr = year + "-" + month + "-" + day;
			org.form.elements[cur].value = dstr;
			if (org.form.elements[nxt][0].checked == true) {
				org.form.elements[nxt][0].focus();
			} else {
				org.form.elements[nxt][1].focus();
			}
		} else if (dstr == "") {
			if (org.form.elements[nxt][0].checked == true) {
				org.form.elements[nxt][0].focus();
			} else {
				org.form.elements[nxt][1].focus();
			}
		} else {
//			alert("入力が正しくありません。");
//			org.form.elements[cur].focus();
			if (org.form.elements[nxt][0].checked == true) {
				org.form.elements[nxt][0].focus();
			} else {
				org.form.elements[nxt][1].focus();
			}
		}

		return false;
	} else {
		return true;
	}
}

//テキストボックスからフォーカスが外れたときの日付補完処理
//引数：
//		org：親フォーム（thisを入れておく)
//		nxt：ジャンプ先の部品名（使用していない：EnterFocusDateを元に作ったので残っている消していいです）
//		cur：現在の部品名
//		def：空文字の場合には空文字を許可する
//戻り値：画面操作の有効・無効（falseでそのキーを押したときの本来の処理を無視する）
function FocusDate(org, nxt, cur, def)
{
	var str;
	var dstr;
	var year;
	var month;
	var day;
	var sub1;
	var sub2;
	var sub3;
	var wrk_str;
	var dlm;
	var today = new Date();

//	if(window.event.keyCode==13 || window.event.keyCode==9){
		str = org.form.elements[cur].value;

		if (str == "") {
			if (def != "") {
//				str = def;
				alert("日付は必ず入力してください。");
				org.form.elements[cur].focus();
				return false;
			}
		}

		dstr = str;


		if (dstr== "") {
		} else if (!str.match(/[^0-9]+/)) {
			if (str.length == 1) {
				year = today.getFullYear();
				month = today.getMonth() + 1;
				day = "0"+str;
			} else if (str.length == 2) {
				year = today.getFullYear();
				month = today.getMonth() + 1;
				day = str;
			} else if (str.length == 4) {
				year = today.getFullYear();
				month = str.substr(0, 2);
				day = str.substr(2, 2);
			} else if (str.length == 8) {
				year = str.substr(0, 4);
				month = str.substr(4, 2);
				day = str.substr(6, 2);
			}
		} else {
			wrk_str = str.replace(/[^0-9]/g, '-');

			dlm = wrk_str.indexOf("-");

			if (dlm < 0) {
			} else {
				sub1 = wrk_str.substr(0, dlm);
				wrk_str = wrk_str.substr(dlm + 1);
				dlm = wrk_str.indexOf("-");

				if (dlm < 0) {
					sub2 = wrk_str;
					sub3 = "";
				} else {
					sub2 = wrk_str.substr(0, dlm);
					sub3 = wrk_str.substr(dlm + 1);
				}
			}

			if (!sub1.match(/[^0-9]+/) && !sub2.match(/[^0-9]+/) && !sub3.match(/[^0-9]+/) && sub3 != "") {
				year = sub1;
				month = ("0"+sub2).slice(-2);
				day = ("0"+sub3).slice(-2);
			} else if (!sub1.match(/[^0-9]+/) && !sub2.match(/[^0-9]+/)) {
				year = today.getFullYear();
				month = ("0"+sub1).slice(-2);
				day = ("0"+sub2).slice(-2);
			}
		}

		dt = new Date(parseInt(year), parseInt(month) - 1, parseInt(day));

		if (dt.getFullYear()==year && dt.getMonth()==month-1 && dt.getDate()==day) {
			dstr = year + "-" + month + "-" + day;
			org.form.elements[cur].value = dstr;
//			org.form.elements[nxt].focus();
		} else if (dstr == "") {
//			org.form.elements[nxt].focus();
		} else {
			alert("入力が正しくありません。");
				org.form.elements[cur].focus();
		}

//		return false;
//	} else {
		return true;
//	}
}

//テキストボックスでエンターキーorタブキーを押したときのジャンプ処理（コード補完付き）
//引数：
//		org：親フォーム（thisを入れておく)
//		nxt：ジャンプ先の部品名
//		cur：現在の部品名
//		head：コードの先頭文字（例：商品コードの場合はD）
//		max：先頭文字を含めた最大文字数
//		equal_flg：文字数の制限
//					true：maxと同じでなければならない
//					false：max以下であればよい
//戻り値：キー処理の有効・無効（falseでそのキーを押したときの本来の処理を無視する）
function EnterFocusCD(org, nxt, cur, head, max, equal_flg)
{
	var str;

	if(window.event.keyCode==13 || window.event.keyCode==9){
		str = org.form.elements[cur].value;

		if (str == "") {
			org.form.elements[nxt].focus();
		} else if (!str.match(/[^0-9]+/)) {
			if ( (str.length + head.length == max) && (equal_flg == true) ) {
				str = head + str;
				org.form.elements[cur].value = str;
				org.form.elements[nxt].focus();
			} else if ( (str.length + head.length <= max) && (equal_flg == false) ) {
				str = head + str;
				org.form.elements[cur].value = str;
				org.form.elements[nxt].focus();
			} else {
//				alert("入力が正しくありません。");
//				org.form.elements[cur].focus();
				org.form.elements[nxt].focus();
			}
		} else {
			if ( (head != "") && (str.substr(0, head.length) == head) ) {
				org.form.elements[nxt].focus();
			} else {
//				alert("入力が正しくありません。");
//				org.form.elements[cur].focus();
				org.form.elements[nxt].focus();
			}
		}

		return false;
	} else {
		return true;
	}
}

//テキストボックスでエンターキーorタブキーを押したときのジャンプ処理（コード補完付き）(ジャンプ先がラジオボタンの場合)
//引数：
//		org：親フォーム（thisを入れておく)
//		nxt：ジャンプ先の部品名
//		cur：現在の部品名
//		head：コードの先頭文字（例：商品コードの場合はD）
//		max：先頭文字を含めた最大文字数
//		equal_flg：文字数の制限
//					true：maxと同じでなければならない
//					false：max以下であればよい
//戻り値：キー処理の有効・無効（falseでそのキーを押したときの本来の処理を無視する）
function EnterFocusCDR(org, nxt, cur, head, max, equal_flg)
{
	var str;

	if(window.event.keyCode==13 || window.event.keyCode==9){
		str = org.form.elements[cur].value;

		if (str == "") {
			if (org.form.elements[nxt][0].checked == true) {
				org.form.elements[nxt][0].focus();
			} else {
				org.form.elements[nxt][1].focus();
			}
		} else if (!str.match(/[^0-9]+/)) {
			if ( (str.length + head.length == max) && (equal_flg == true) ) {
				str = head + str;
				org.form.elements[cur].value = str;
				if (org.form.elements[nxt][0].checked == true) {
					org.form.elements[nxt][0].focus();
				} else {
					org.form.elements[nxt][1].focus();
				}
			} else if ( (str.length + head.length <= max) && (equal_flg == false) ) {
				str = head + str;
				org.form.elements[cur].value = str;
				if (org.form.elements[nxt][0].checked == true) {
					org.form.elements[nxt][0].focus();
				} else {
					org.form.elements[nxt][1].focus();
				}
			} else {
//				alert("入力が正しくありません。");
//				org.form.elements[cur].focus();
				if (org.form.elements[nxt][0].checked == true) {
					org.form.elements[nxt][0].focus();
				} else {
					org.form.elements[nxt][1].focus();
				}
			}
		} else {
			if ( (head != "") && (str.substr(0, head.length) == head) ) {
				org.form.elements[nxt].focus();
			} else {
//				alert("入力が正しくありません。");
//				org.form.elements[cur].focus();
				if (org.form.elements[nxt][0].checked = true) {
					org.form.elements[nxt][0].focus();
				} else {
					org.form.elements[nxt][1].focus();
				}
			}
		}

		return false;
	} else {
		return true;
	}
}

//テキストボックスからフォーカスが外れたときのコード補完処理
//引数：
//		org：親フォーム（thisを入れておく)
//		nxt：ジャンプ先の部品名（使用していない：EnterFocusDateを元に作ったので残っている消していいです）
//		cur：現在の部品名
//		head：コードの先頭文字（例：商品コードの場合はD）
//		max：先頭文字を含めた最大文字数
//		equal_flg：文字数の制限
//					true：maxと同じでなければならない
//					false：max以下であればよい
//戻り値：画面操作の有効・無効（falseでそのキーを押したときの本来の処理を無視する）
function FocusCD(org, nxt, cur, head, max, equal_flg)
{
	var str;

//	if(window.event.keyCode==13 || window.event.keyCode==9){
		str = org.form.elements[cur].value;

		if (str == "") {
//			org.form.elements[nxt].focus();
		} else if (!str.match(/[^0-9]+/)) {
			if ( (str.length + head.length == max) && (equal_flg == true) ) {
				str = head + str;
				org.form.elements[cur].value = str;
//				org.form.elements[nxt].focus();
			} else if ( (str.length + head.length <= max) && (equal_flg == false) ) {
				str = head + str;
				org.form.elements[cur].value = str;
//				org.form.elements[nxt].focus();
			} else {
				alert("入力が正しくありません。");
				org.form.elements[cur].focus();
			}
		} else {
			if ( (head != "") && (str.substr(0, head.length) == head) ) {
//				org.form.elements[nxt].focus();
			} else {
				alert("入力が正しくありません。");
				org.form.elements[cur].focus();
			}
		}

//		return false;
//	} else {
		return true;
//	}
}

//リスト内でクリックしたコードをテキストボックスslip_no1とslip_no2に入れる（onclickと組み合わせて使用）
//引数：
//	val:クリックしたコードの値
//戻り値：なし
function InputSlipNo(val)
{
	if((document.forms['form1'].elements['slip_no1'].value == "") && (document.forms['form1'].elements['slip_no2'].value == "")) {
		document.forms['form1'].elements['slip_no1'].value=val;
		document.forms['form1'].elements['slip_no2'].value=val;
	}
}

//仕入照会画面でクリックした発注番号を検索値として発注照会画面を開く（onclickと組み合わせて使用）
//引数：
//	val:クリックした発注番号
//戻り値：なし
function LinkSlipNo(val)
{
	document.location = "./order_s.php?slip_no1="+val+"&slip_no2="+val;
}