<?php
/*********************/
/*                   */
/*  Version : 5.1.0  */
/*  Author  : RM     */
/*  Comment : 071223 */
/*                   */
/*********************/

class browse
{

				var $classname = "browse";
				var $database_class = "DB_test";
				var $template_class = "Template";
				var $prgnoware = array( );
				var $prgnowareurl = array( );
				var $browse_rows = 25;
				var $browse_order = "";
				var $browse_ordersn = "";
				var $browse_editname = "查看";
				var $browse_query = "";
				var $browse_queryselect = "";
				var $browse_querywhere = "";
				var $browse_queryorder = "";
				var $browse_querygroupby = "";
				var $browse_field = array( );
				var $browse_check = array( );
				var $browse_delsql = "";
				var $browse_delcontsql = "";
				var $browse_collectquery = "";
				var $browse_collectname = "";
				var $browse_collectdata = "";
				var $browse_new = "";
				var $browse_edit = "";
				var $browse_inputfile = "";
				var $browse_outtoexcel = "";
				var $browse_addqx = "";
				var $browse_editqx = "";
				var $browse_delqx = "";
				var $browse_rowfield = array( );
				var $browse_hidden = array( );
				var $browse_link = array( );
				var $browse_find = array( );
				var $browse_sayfind = "";
				var $browse_showfind = array( );
				var $browse_seldofile = array( );
				var $browse_seldofileval = array( );
				var $browse_haveselectvalue = "";
				var $browse_firstval = "";
				var $browse_findnum = 5;
				var $browse_skin = "";
				var $labelnum = 0;
				var $allcondition = 0;

				function main( $now, $action, $pagerows, $order, $upordown, $checknote, $prgnoware, $prgnowareurl, $pagerows, $whatdofind, $howdofind, $findwhat, $allcondition )
				{
					            
					           
								$this->browse_nowpage = $now;
								$this->browse_order = $order;
								$this->browse_ordersn = $upordown;
								$this->browse_check = $checknote;
								$this->browse_keyword = $keyword;
								$this->browse_rows = $pagerows;
								if ( $this->browse_rows == 0 )
								{
												$this->browse_rows = 25;
								}
								if ( !empty( $this->browse_querygroupby ) )
								{
												$this->browse_querygroupby = "group by ".$this->browse_querygroupby;
								}
								$urlcan = "?now=".$now."&pagerows=".$pagerows."&order=".$order."&upordown=".$upordown;
								$tempurl = rawurlencode( $urlcan );
								$name = $this->database_class;
								$this->db = new $name( );
								$prglist = "";
								$i = 0;
								for ( ;	$i < count( $this->prgnoware );	++$i	)
								{
												if ( empty( $this->prglist ) )
												{
																$this->prglist .= "&nbsp;".$this->prgnoware[$i];
												}
												else
												{
																$this->prglist .= "&nbsp;-->&nbsp;".$this->prgnoware[$i]."\n";
												}
								}
								if ( $action == "delete" )
								{
												$this->dodelete( );
								}
								$fdcount = count( $this->browse_field );
								$i = 0;
								for ( ;	$i < $fdcount;	++$i	)
								{
												$name = $this->browse_field[$i];
												$this->fd[$i] = new $name( );
								}
								$fdrowcount = count( $this->browse_rowfield );
								$i = 0;
								for ( ;	$i < $fdrowcount;	++$i	)
								{
												$name = $this->browse_rowfield[$i];
												$sx_k = $fdcount + $i;
												$this->fd[$sx_k] = new $name( );
								}
								if ( empty( $this->browse_nowpage ) )
								{
												$this->browse_nowpage = 1;
								}
								$this->makewhere( $whatdofind, $howdofind, $findwhat, $allcondition );
								if ( $action == "collect" )
								{
												$this->docollect( );
												$isshowcollecttr = "";
								}
								else
								{
												$isshowcollecttr = "none";
								}
								$query = $this->makequery( "all" );
								$this->db->query( $query );
								$this->browse_total = $this->db->nf( );
								$this->browse_pages = ceil( $this->browse_total / $this->browse_rows );
								if ( $this->browse_pages < $this->browse_nowpage )
								{
												$this->browse_nowpage = $this->browse_pages;
								}
								$this->browse_rowfrom = ( $this->browse_nowpage - 1 ) * $this->browse_rows;
								$showviewpage = $this->pagination( $this->browse_nowpage, $this->browse_pages );
								$this->makeorder( $fdcount );
								$query = $this->makequery( "now" );
								$this->db->query( $query );
								$lkcount = count( $this->browse_link );
								$i = 0;
								for ( ;	$i < $lkcount;	++$i	)
								{
												$name = $this->browse_link[$i];
												$this->lk[$i] = new $name( );
								}
								$titlearr = $this->showtitle( $fdcount, $lkcount );
								$TrColor = "";
								while ( $this->db->next_record( ) )
								{
												$key = $this->db->f( $this->browse_key );
												$i = 0;
												for ( ;	$i < $fdcount;	++$i	)
												{
																$this->fd[$i]->bwfd_value = $this->db->f( $this->fd[$i]->bwfd_fdname );
																$j = 0;
																for ( ;	$j < count( $this->fd[$i]->bwfd_need );	++$j	)
																{
																				$this->fd[$i]->bwfd_need[$j][1] = $this->db->f( $this->fd[$i]->bwfd_need[$j][0] );
																}
												}
												$sx_j = 0;
												for ( ;	$sx_j < $fdrowcount;	++$sx_j	)
												{
																$i = $sx_j + $fdcount;
																$this->fd[$i]->bwfd_value = $this->db->f( $this->fd[$i]->bwfd_fdname );
																$j = 0;
																for ( ;	$j < count( $this->fd[$i]->bwfd_need );	++$j	)
																{
																				$this->fd[$i]->bwfd_need[$j][1] = $this->db->f( $this->fd[$i]->bwfd_need[$j][0] );
																}
												}
												$i = 0;
												for ( ;	$i < $lkcount;	++$i	)
												{
																$j = 0;
																for ( ;	$j < count( $this->lk[$i]->bwlk_fdname );	++$j	)
																{
																				$this->lk[$i]->bwlk_fdname[$j][1] = $this->db->f( $this->lk[$i]->bwlk_fdname[$j][0] );
																}
												}
												++$this->labelnum;
												$showline = $this->showline( $fdcount, $key );
												$showline .= $this->showlink( $lkcount, $key );
												$TrColor = $this->trbgcolor( $TrColor );
												$td_content = "";
												$tr_i = 0;
												for ( ;	$tr_i < $fdrowcount;	++$tr_i	)
												{
																$tr_k = $fdcount + $tr_i;
																$td_content .= $this->showtrfield( $TrColor, $tr_k, $fdcount, $lkcount );
												}
												$linearr[] = array(
																"bgcolor" => $TrColor,
																"xi" => $showline,
																"td_con" => $td_content,
																"labelnum" => $this->labelnum
												);
								}
								if ( empty( $this->browse_new ) || $this->browse_addqx != 1 )
								{
												$shownew = "";
												$showdisnew = "none";
								}
								else
								{
												$shownew = $this->makenew( );
												$showdisnew = "";
								}
								if ( empty( $this->browse_edit ) || $this->browse_editqx != 1 )
								{
												$showalledit = "";
												$showalldisedit = "none";
								}
								else
								{
												$showalledit = $this->makealledit( );
												$showalldisedit = "";
								}
								if ( empty( $this->browse_delsql ) || $this->browse_delqx != 1 )
								{
												$showdel = "";
												$showdisdel = "none";
								}
								else
								{
												$showdel = $this->makedel( );
												$showdisdel = "";
								}
								if ( $showalldisedit == "none" && $showdisdel == "none" )
								{
												$showcountforlist = "style='display:none'";
								}
								if ( empty( $this->browse_inputfile ) )
								{
												$inputfile = "";
												$disinputfile = "none";
								}
								else
								{
												$inputfile = $this->browse_inputfile;
												$disinputfile = "";
								}
								if ( empty( $this->browse_outtoexcel ) )
								{
												$outtoexcle = "";
												$disoutexcle = "none";
								}
								else
								{
												$outtoexcle = $this->browse_outtoexcel;
												$disoutexcle = "";
								}
								switch ( $allcondition )
								{
								case "0" :
												$isallcheck = "";
												$iseithercheck = "checked";
												break;
								case "1" :
												$isallcheck = "checked";
												$iseithercheck = "";
												break;
								default :
												$isallcheck = "checked";
												$iseithercheck = "";
												break;
								}
								$showhidden = $this->makehidden( );
								$seldofile_num = count( $this->browse_seldofile );
								$arr_seldofile = $this->browse_seldofile;
								$arr_seldofileval = $this->browse_seldofileval;
								$haveselectvalue = $this->browse_haveselectvalue;
								$seldofile = "";
								$flagissel = 0;
								$i = 0;
								for ( ;	$i < $seldofile_num;	++$i	)
								{
												if ( $haveselectvalue == $arr_seldofileval[$i] )
												{
																$seldofile .= "<option selected value='".$arr_seldofileval[$i]."'>".$arr_seldofile[$i]."</option>";
												}
												else
												{
																$seldofile .= "<option value='".$arr_seldofileval[$i]."'>".$arr_seldofile[$i]."</option>";
												}
												$flagissel = 1;
								}
								if ( $flagissel == 1 )
								{
												$displaysel = "";
								}
								else
								{
												$displaysel = "none";
								}
								$this->browse_rowto = $this->browse_rowfrom + $this->db->nf( );
								$this->showfind( $whatdofind, $howdofind, $findwhat );
								$this->t = new template( ".", "keep" );
								$this->t->set_file( "browse", "../include/have_collect_browse.html" );
								$this->t->set_var( "prglist", $this->prglist );
								$this->t->set_var( "findsay", $this->browse_sayfind );
								$this->t->set_var( "disnew", $showdisnew );
								$this->t->set_var( "shownew", $shownew );
								$this->t->set_var( "disedit", $showalldisedit );
								$this->t->set_var( "showedit", $showalledit );
								$this->t->set_var( "disdel", $showdisdel );
								$this->t->set_var( "showdel", $showdel );
								$this->t->set_var( "inputfile", $inputfile );
								$this->t->set_var( "disinputfile", $disinputfile );
								$this->t->set_var( "outtoexcle", $outtoexcle );
								$this->t->set_var( "disoutexcle", $disoutexcle );
								$this->t->set_var( "browse_collectdata", $this->browse_collectdata );
								$this->t->set_var( "isshowcollecttr", $isshowcollecttr );
								$this->t->set_var( "showhidden", $showhidden );
								$this->t->set_var( "showcountforlist", $showcountforlist );
								$this->t->set_var( "total", $this->browse_total );
								$this->t->set_var( "pages", $this->browse_pages );
								$this->t->set_var( "nowpage", $this->browse_nowpage );
								$this->t->set_var( "showviewpage", $showviewpage );
								$this->t->set_var( "rows", $this->browse_rows );
								$this->t->set_var( "order", $this->browse_order );
								$this->t->set_var( "upordown", $this->browse_ordersn );
								$this->t->set_var( "recodeform", $this->browse_rowfrom );
								$this->t->set_var( "recodeto", $this->browse_rowto );
								$this->t->set_var( "seldofile", $seldofile );
								$this->t->set_var( "displaysel", $displaysel );
								$this->t->set_var( "firstval", $this->browse_firstval );
								$this->t->set_var( "isallcheck", $isallcheck );
								$this->t->set_var( "iseithercheck", $iseithercheck );
								$this->t->set_var( "url", $tempurl );
								$this->t->set_var( "skin", $this->browse_skin );
								$this->t->set_block( "browse", "TITLEBK", "titlebks" );
								$i = 0;
								for ( ;	$i < count( $titlearr );	++$i	)
								{
												$this->t->set_var( array(
																"title" => $titlearr[$i][title],
																"titlecss" => $titlearr[$i][titlecss],
																"cols" => $titlearr[$i][cols]
												) );
												$this->t->parse( "titlebks", "TITLEBK", true );
								}
								$this->t->set_block( "browse", "DATABK", "databks" );
								$n = count( $linearr );
								if ( 0 < $n )
								{
												$i = 0;
												for ( ;	$i < $n;	++$i	)
												{
																$this->t->set_var( array(
																				"bgcolor" => $linearr[$i][bgcolor],
																				"xi" => $linearr[$i][xi],
																				"td_content" => $linearr[$i][td_con],
																				"labelnum" => $linearr[$i][labelnum]
																) );
																$this->t->parse( "databks", "DATABK", true );
												}
								}
								else
								{
												$this->t->set_var( array( "bgcolor" => "", "xi" => "", "td_content" => "" ) );
												$this->t->parse( "databks", "DATABK", true );
								}
								$this->t->set_block( "browse", "FINDBK", "findbks" );
								$i = 0;
								for ( ;	$i < $this->browse_findnum;	++$i	)
								{
												$this->t->set_var( array(
																"whatdofind" => $this->browse_showfind[$i][0],
																"howdofind" => $this->browse_showfind[$i][1],
																"findwhat" => $this->browse_showfind[$i][2]
												) );
												$this->t->parse( "findbks", "FINDBK", true );
								}
								$this->t->pparse( "out", "browse" );
				}

				function makequery( $isall )
				{
								if ( $isall == "all" )
								{
												$query = $this->browse_queryselect." ".$this->browse_querywhere." ".$this->browse_querygroupby;
								}
								else
								{
												if ( $this->browse_rowfrom < 0 )
												{
																$this->browse_rowfrom = 0;
												}
												$query = $this->browse_queryselect." ".$this->browse_querywhere." ".$this->browse_querygroupby." ".$this->browse_queryorder." limit ".$this->browse_rowfrom." , ".$this->browse_rows;
								}
								return $query;
				}

				function pagination( $nows, $all )
				{
								$show = "";
								$showend = "";
								if ( 1 < $nows )
								{
												$show .= "<input type='button' name='first_page' class='first_page' onclick='javascript:viewPage(1)'>\n";
								}
								else
								{
												$show .= "&nbsp;<input type='button' name='first_page_gray' class='first_page_gray' >\n";
								}
								if ( $nows < $all )
								{
												$showend .= "<input type='button' name='last_page' class='last_page' onclick='javascript:viewPage({$all})'>\n";
								}
								else
								{
												$showend .= "&nbsp;<input type='button' name='last_page_gray' class='last_page_gray' >\n";
								}
								$k = $nows + 1;
								$j = $nows - 1;
								if ( 0 < $j )
								{
												$show .= "<input type='button' name='front_page' class='front_page' onclick='javascript:viewPage({$j})'>\n";
								}
								else
								{
												$show .= "&nbsp;<input type='button' name='front_page_gray' class='front_page_gray' >\n";
								}
								if ( $k <= $all )
								{
												$show .= "<input type='button' name='next_page' class='next_page' onclick='javascript:viewPage({$k})'>\n";
								}
								else
								{
												$show .= "&nbsp;<input type='button' name='next_page_gray' class='next_page_gray' >\n";
								}
								return $show.$showend;
				}

				function showtitle( $n, $m )
				{
								$i = 0;
								for ( ;	$i < $n;	++$i	)
								{
												$show = "<a class = title href=\"javascript:submit_order('".$this->browse_field[$i]."')\"> ".$this->fd[$i]->bwfd_title."</a>";
												$titlearr[] = array(
																"title" => $show,
																"titlecss" => $this->fd[$i]->bwfd_titlecss,
																"cols" => ""
												);
								}
								if ( !empty( $this->browse_edit ) && $this->browse_editqx == 1 )
								{
												++$m;
								}
								if ( 0 < $m )
								{
												$cols = "colspan='".$m."'";
												$titlearr[] = array(
																"title" => "操作区",
																"titlecss" => "listcelltitle",
																"cols" => $cols
												);
								}
								return $titlearr;
				}

				function showline( $n, $key )
				{
								if ( ( empty( $this->browse_edit ) || $this->browse_editqx != 1 ) && ( empty( $this->browse_delsql ) || $this->browse_delqx != 1 ) )
								{
												$showcountforlist = "display:none";
								}
								$show = "<label for=arrlabel[".$this->labelnum."] oncontextmenu = showMenu()><TD class=listcellrow style='WIDTH: 10px;".$showcountforlist."'><input type='checkbox' id=arrlabel[".$this->labelnum."] name='checknote[]' value='{$key}' onclick= changebg('arrlabel[".$this->labelnum."]')></td>";
								$i = 0;
								for ( ;	$i < $n;	++$i	)
								{
												$fd_show = $this->fd[$i]->makeshow( );
												$fd_align = $this->fd[$i]->bwfd_align;
												$fd_nowrap = $this->fd[$i]->bwfd_nowrap;
												$fd_link = $this->fd[$i]->bwfd_link;
												$fd_isneetkey = $this->fd[$i]->bwfd_isneetkey;
												if ( $fd_isneetkey == 1 )
												{
																$fd_link = $fd_link.$key;
												}
												if ( !empty( $fd_link ) )
												{
																$show .= "<TD class=listcellrow ".$fd_nowrap." align={$fd_align} height='23'><a href='{$fd_link}' >{$fd_show}</a></TD> ";
												}
												else
												{
																$show .= "<TD class=listcellrow ".$fd_nowrap." align={$fd_align} height='23'>{$fd_show}</TD> ";
												}
								}
								return $show." ";
				}

				function showtrfield( $bgcolor, $tr_k, $fdcount, $lkcount )
				{
								$fd_show = $this->fd[$tr_k]->makeshow( );
								$fd_align = $this->fd[$tr_k]->bwfd_align;
								$fd_nowrap = $this->fd[$tr_k]->bwfd_nowrap;
								$colspan = $fdcount + $lkcount;
								$fd_title = $this->fd[$tr_k]->bwfd_title;
								if ( !empty( $this->browse_edit ) && $this->browse_editqx == 1 )
								{
												$colspan = 1 + $colspan;
								}
								$show = "<TR class=listrow1 onmouseover=this.style.backgroundColor='#DAE2ED'; onmouseout=this.style.backgroundColor='".$bgcolor."' bgcolor=".$bgcolor.">";
								$show .= "<TD class=listcellrow ".$fd_nowrap." align=".$fd_align."></TD> ";
								$show .= "<TD colspan=".$colspan." class=listcellrow ".$fd_nowrap." align=".$fd_align.">".$fd_title."：".$fd_show."</TD> ";
								return $show."</TR>";
				}

				function showlink( $n, $key )
				{
								$show = "";
								if ( !empty( $this->browse_edit ) && $this->browse_editqx == 1 )
								{
												$tmpshow = $this->makeedit( $key );
												$show .= "<TD class=listcellrow noWrap align=center>".$tmpshow."</TD>";
								}
								$i = 0;
								for ( ;	$i < $n;	++$i	)
								{
												$lk_show = $this->lk[$i]->makelink( );
												$show .= "<TD class=listcellrow noWrap align=center>".$lk_show."</TD>";
								}
								return $show;
				}

				function trbgcolor( $TrColor )
				{
								if ( $TrColor == "#FFFFFF" )
								{
												$TrColor = "#EEF2FF";
								}
								else
								{
												$TrColor = "#FFFFFF";
								}
								return $TrColor;
				}

				function makeedit( $key )
				{
								$edit = "";
								if ( !empty( $this->browse_edit ) )
								{
												$edit = "<a href=javascript:linkurl(\"".$this->browse_edit.$key."\")>".$this->browse_editname."</a>";
								}
								return $edit;
				}

				function makenew( )
				{
								return $this->browse_new;
				}

				function makealledit( )
				{
								return $this->browse_edit;
				}

				function makedel( )
				{
								return $this->browse_del;
				}

				function dodelete( )
				{
								$i = 0;
								for ( ;	$i < count( $this->browse_check );	++$i	)
								{
												$query = sprintf( $this->browse_delsql, $this->browse_check[$i] );
												$this->db->query( $query );
												if ( !empty( $this->browse_delcontsql ) )
												{
																$query = sprintf( $this->browse_delcontsql, $this->browse_check[$i] );
																$this->db->query( $query );
												}
								}
				}

				function docollect( )
				{
								$this->db->query( $this->browse_collectquery );
								if ( $this->db->nf( ) )
								{
												$this->db->next_record( );
												$collectmoney = $this->db->f( collectmoney );
												$collectmoney = number_format( $collectmoney, 2, ".", "");
								}
								else
								{
												$collectmoney = 0;
								}
								$this->browse_collectdata = $this->browse_collectname.$collectmoney;
				}

				function makeorder( $n )
				{
								$show = "";
								$i = 0;
								for ( ;	$i < $n;	++$i	)
								{
												if ( $this->browse_field[$i] == $this->browse_order )
												{
																$show = " order by ".$this->fd[$i]->getorder( $this->browse_ordersn );
												}
								}
								$this->browse_queryorder = $show;
				}

				function makehidden( )
				{
								$hiddenvalue = "";
								$j = 0;
								for ( ;	$j < count( $this->browse_hidden );	++$j	)
								{
												$hiddenname = $this->browse_hidden[$j][0];
												$keyfield = $this->browse_hidden[$j][1];
												$hiddenvalue .= "<input type=hidden name='".$hiddenname."' value=".$keyfield." > ";
								}
								return $hiddenvalue;
				}

				function showfind( $whatdofind, $howdofind, $findwhat )
				{
								$i = 0;
								for ( ;	$i < $this->browse_findnum;	++$i	)
								{
												$findtitle = "";
												$findhow = "";
												$j = 0;
												for ( ;	$j < count( $this->browse_find );	++$j	)
												{
																if ( $whatdofind[$i] == $this->browse_find[$j][1] )
																{
																				$findtitle .= "<option selected value='".$this->browse_find[$j][1]."'>".$this->browse_find[$j][0]."</option>";
																}
																else
																{
																				$findtitle .= "<option value='".$this->browse_find[$j][1]."'>".$this->browse_find[$j][0]."</option>";
																}
												}
												if ( $howdofind[$i] == ">" )
												{
																$findhow .= "<option selected value='>'>>&nbsp;&nbsp;&nbsp;&nbsp;大于</option>";
												}
												else
												{
																$findhow .= "<option value='>'>>&nbsp;&nbsp;&nbsp;&nbsp;大于</option>";
												}
												if ( $howdofind[$i] == ">=" )
												{
																$findhow .= "<option selected value='>='>>=&nbsp;&nbsp;&nbsp;大于等于</option>";
												}
												else
												{
																$findhow .= "<option value='>='>>=&nbsp;&nbsp;&nbsp;大于等于</option>";
												}
												if ( $howdofind[$i] == "=" )
												{
																$findhow .= "<option selected value='='>=&nbsp;&nbsp;&nbsp;&nbsp;等于</option>";
												}
												else
												{
																$findhow .= "<option value='='>=&nbsp;&nbsp;&nbsp;&nbsp;等于</option>";
												}
												if ( $howdofind[$i] == "<=" )
												{
																$findhow .= "<option selected value='<='><=&nbsp;&nbsp;&nbsp;小于等于</option>";
												}
												else
												{
																$findhow .= "<option value='<='><=&nbsp;&nbsp;&nbsp;小于等于</option>";
												}
												if ( $howdofind[$i] == "<" )
												{
																$findhow .= "<option selected value='<'><&nbsp;&nbsp;&nbsp;&nbsp;小于</option>";
												}
												else
												{
																$findhow .= "<option value='<'><&nbsp;&nbsp;&nbsp;&nbsp;小于</option>";
												}
												if ( $howdofind[$i] == "like" )
												{
																$findhow .= "<option selected value='like'>LIKE 包含</option>";
												}
												else
												{
																$findhow .= "<option value='like'>LIKE 包含</option>";
												}
												if ( $howdofind[$i] == "not like" )
												{
																$findhow .= "<option selected value='not like'>NOIN 不包含</option>";
												}
												else
												{
																$findhow .= "<option value='not like'>NOIN 不包含</option>";
												}
												if ( $howdofind[$i] == "<>" )
												{
																$findhow .= "<option selected value='<>'><>&nbsp;&nbsp;&nbsp;不等于</option>";
												}
												else
												{
																$findhow .= "<option value='<>'><>&nbsp;&nbsp;&nbsp;不等于</option>";
												}
												$this->browse_showfind[$i][0] = $findtitle;
												$this->browse_showfind[$i][1] = $findhow;
												$this->browse_showfind[$i][2] = $findwhat[$i];
								}
				}

				function makewhere( $whatdofind, $howdofind, $findwhat, $allcondition )
				{
								$show = $this->browse_querywhere;
								$isquerywhere = $this->browse_querywhere;
								$showsay = "";
								$whereflag = 0;
								$contentflag = 0;
								$i = 0;
								for ( ;	$i < count( $whatdofind );	++$i	)
								{
												$tmp = $this->makefindsql( $whatdofind[$i], $howdofind[$i], $findwhat[$i] );
												if ( !empty( $isquerywhere ) && $whereflag == 0 && !empty( $whatdofind[$i] ) && !empty( $tmp ) )
												{
																$show .= " and ( ";
																$whereflag = 1;
												}
												if ( !empty( $tmp ) )
												{
																$say = $this->makefindsay( $whatdofind[$i], $howdofind[$i], $findwhat[$i] );
																if ( empty( $show ) )
																{
																				$show .= $tmp;
																				$showsay .= $say;
																}
																else if ( $allcondition == 1 )
																{
																				if ( $contentflag == 1 )
																				{
																								$show .= " and ".$tmp;
																								$showsay .= " 并且 ".$say;
																				}
																				else
																				{
																								$show .= " ".$tmp;
																								$contentflag = 1;
																								$showsay .= " ".$say;
																				}
																}
																else if ( $contentflag == 1 )
																{
																				$show .= " or ".$tmp;
																				$showsay .= " 或者 ".$say;
																}
																else
																{
																				$show .= " ".$tmp;
																				$showsay .= " ".$say;
																				$contentflag = 1;
																}
												}
								}
								if ( !empty( $isquerywhere ) && $whereflag == 1 )
								{
												$show .= " ) ";
								}
								if ( !empty( $show ) )
								{
												$this->browse_querywhere = "where ".$show;
								}
								if ( !empty( $showsay ) )
								{
												$this->browse_sayfind = "<TABLE  cellSpacing=0 cellPadding=0 width='98%' align=center border=0><TBODY>";
												$this->browse_sayfind .= "<TR vAlign=center><TD  class=listtai2 align=left>";
												$this->browse_sayfind .= "当前查询条件:".$showsay;
												$this->browse_sayfind .= "</TD></TR></TBODY></TABLE>";
								}
				}

				function makefindsql( $whatdofind, $howdofind, $findwhat )
				{
								$show = "";
								$findwhat = strval( $findwhat );
								if ( !empty( $whatdofind ) && !empty( $howdofind ) && ( !empty( $findwhat ) || $findwhat == 0 ) )
								{
												if ( $howdofind == "like" || $howdofind == "not like" )
												{
																$show = $whatdofind." ".$howdofind." '%".$findwhat."%'";
												}
												else
												{
																$show = $whatdofind." ".$howdofind." '".$findwhat."'";
												}
								}
								return $show;
				}

				function makefindsay( $whatdofind, $howdofind, $findwhat )
				{
								$i = 0;
								for ( ;	$i < count( $this->browse_find );	++$i	)
								{
												if ( $this->browse_find[$i][1] == $whatdofind )
												{
																$show = $this->browse_find[$i][0];
																switch ( $howdofind )
																{
																case ">" :
																				$show .= "&nbsp;大于";
																				break;
																case ">=" :
																				$show .= "&nbsp;大于等于";
																				break;
																case "=" :
																				$show .= "&nbsp;等于";
																				break;
																case "<=" :
																				$show .= "&nbsp;小于等于";
																				break;
																case "<" :
																				$show .= "&nbsp;小于";
																				break;
																case "like" :
																				$show .= "&nbsp;包含";
																				break;
																case "not like" :
																				$show .= "&nbsp;不包含";
																				break;
																case "<>" :
																				$show .= "&nbsp;不等于";
																				break;
																default :
																				break;
																}
																$show .= $findwhat." ";
												}
								}
								return $show;
				}

				function makeselectfile( $arritem, $hadselected, $arry )
				{
								$x .= "<option value=''>请选择</option>";
								$i = 0;
								for ( ;	$i < count( $arritem );	++$i	)
								{
												if ( $hadselected == $arry[$i] )
												{
																$x .= "<option selected value='{$arry[$i]}'>".$arritem[$i]."</option>";
												}
												else
												{
																$x .= "<option value='{$arry[$i]}'>".$arritem[$i]."</option>";
												}
								}
								return $x;
				}

}

class browsefield
{

				var $classname = "browsefield";
				var $bwfd_titlecss = "listcelltitle";
				var $bwfd_need = array( );
				var $bwfd_align = "left";
				var $bwfd_fdcss = "fd";
				var $bwfd_order = "";
				var $bwfd_nowrap = "noWrap";
				var $bwfd_format = "";

				function makeshow( )
				{
								$showvalue = $this->bwfd_value;
								if ( $this->bwfd_format == "num" )
								{
												$showvalue = number_format( $showvalue, 2, ".", "");
												$this->bwfd_align = "right";
								}
								return $showvalue;
				}

				function getorder( $done )
				{
								if ( $done == "asc" )
								{
												$this->bwfd_title .= " <font face=webdings>5</font>";
								}
								else
								{
												$this->bwfd_title .= " <font face=webdings>6</font>";
								}
								return $this->bwfd_fdname." ".$done." ";
				}

}

class browselink
{

				var $classname = "browselink";
				var $bwlk_fdname = array( );
				var $bwlk_linkcss = "listcelltitle";
				var $bwlk_text = "";
				var $bwlk_prgname = "";
				var $bwlk_is_blank = 0;

				function makeprg( )
				{
								if ( !empty( $this->bwlk_fdname[0][1] ) )
								{
												$prg = $this->bwlk_prgname.$this->bwlk_fdname[0][1];
								}
								else
								{
												$prg = "";
								}
								return $prg;
				}

				function makelink( )
				{
								$linkurl = $this->makeprg( );
								if ( !empty( $linkurl ) )
								{
												if ( $this->bwlk_is_blank == 0 )
												{
																$link = "<a href=javascript:linkurl(\"".$linkurl."\")>".$this->bwlk_title."</a>";
												}
												else
												{
																$link = "<a href=javascript:windowopen(\"".$linkurl."\")>".$this->bwlk_title."</a>";
												}
								}
								else
								{
												$link = "";
								}
								return $link;
				}

}

session_register( "session_analyseorganid" );
session_register( "session_begindate" );
session_register( "session_enddate" );
if ( empty( $session_analyseorganid ) )
{
				if ( empty( $begindate ) )
				{
								$begindate = $session_begindate;
								$enddate = $session_enddate;
								$analyseorganid = "";
				}
}
else if ( empty( $analyseorganid ) )
{
				$analyseorganid = $session_analyseorganid;
}
$session_analyseorganid = $analyseorganid;
$session_begindate = $begindate;
$session_enddate = $enddate;
session_register( "find_whatdofind" );
session_register( "find_howdofind" );
session_register( "find_findwhat" );
if ( !empty( $whatdofind[0] ) && !empty( $howdofind[0] ) && !empty( $findwhat[0] ) )
{
				$find_whatdofind = $whatdofind;
				$find_howdofind = $howdofind;
				$find_findwhat = $findwhat;
}
else
{
				$whatdofind = $find_whatdofind;
				$howdofind = $find_howdofind;
				$findwhat = $find_findwhat;
}
session_unregister( "selecteditvalue" );
unset( $selecteditvalue );
?>
