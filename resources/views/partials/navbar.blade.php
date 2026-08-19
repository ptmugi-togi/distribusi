<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link " href="/">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#master-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-menu-button-wide"></i><span>Master</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="master-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
              <a href="/mbrand">
              <i class="bi bi-circle"></i><span>Brand</span>
              </a>
          </li>  
          <li>
              <a href="/mbranch">
              <i class="bi bi-circle"></i><span>Branch</span>
              </a>
          </li>
          <li>
              <a href="/mcls">
              <i class="bi bi-circle"></i><span>Class</span>
              </a>
          </li>
          <li>
              <a href="/mcindu">
              <i class="bi bi-circle"></i><span>Customer Industry</span>
              </a>
          </li>
          <li>
              <a href="/cusmas">
              <i class="bi bi-circle"></i><span>Customer Master</span>
              </a>
          </li>
          <li>
              <a href="/mdepo">
              <i class="bi bi-circle"></i><span>Depo</span>
              </a>
          </li>
          <li>
              <a href="{{ route('formc.index') }}">
              <i class="bi bi-circle"></i><span>Form Code</span>
              </a>
          </li>
          <li>
              <a href="/mitype">
              <i class="bi bi-circle"></i><span>Inventory Type</span>
              </a>
          </li>
          <li>
              <a href="/mpromas">
              <i class="bi bi-circle"></i><span>Product</span>
              </a>
          </li>
          <li>
              <a href="/mpgrup">
              <i class="bi bi-circle"></i><span>Product Group</span>
              </a>
          </li>
          <?php if(auth()->user()->level=="IT"){ ?>
            <li class="{{ request()->is('register') ? 'active' : '' }}">
                <a href="/register">
                <i class="bi bi-circle"></i><span>REGISTER</span>
                </a>
            </li>
          <?php } ?>
          <li>
              <a href="/msreno">
              <i class="bi bi-circle"></i><span>Sales Rep</span>
              </a>
          </li>
          <li>
              <a href="/mstmas">
              <i class="bi bi-circle"></i><span>Shipment</span>
              </a>
          </li>
          <li>
              <a href="/msgrup">
              <i class="bi bi-circle"></i><span>SubGroup</span>
              </a>
          </li>
          <li>
              <a href="/ssgrup">
              <i class="bi bi-circle"></i><span>SubSubGroup</span>
              </a>
          </li>
        </ul>
      </li>

      {{-- <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-journal-text"></i><span>Forms</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="/roce">
              <i class="bi bi-circle"></i><span>RETAIL ORDER CONFIRMATION ENTRY</span>
            </a>
          </li>
          <li>
            <a href="forms-layouts.html">
              <i class="bi bi-circle"></i><span>Form Layouts</span>
            </a>
          </li>
          <li>
            <a href="forms-editors.html">
              <i class="bi bi-circle"></i><span>Form Editors</span>
            </a>
          </li>
          <li>
            <a href="forms-validation.html">
              <i class="bi bi-circle"></i><span>Form Validation</span>
            </a>
          </li>
        </ul>
      </li> --}}
      
      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#fna-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-calculator-fill"></i><span>F & A</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="fna-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{ route('cn_dp_project.index') }}">
            <i class="bi bi-circle"></i><span>CN DP/Project</span>
            </a>
          </li>
          <li>
            <a href="{{ route('cn_retail.index') }}">
            <i class="bi bi-circle"></i><span>CN Retail</span>
            </a>
          </li>
          <li>
            <a href="{{ route('cn_teknik.index') }}">
            <i class="bi bi-circle"></i><span>CN Teknik</span>
            </a>
          </li>
          <li>
            <a href="{{ route('dp_inv_rel.index') }}">
            <i class="bi bi-circle"></i><span>DP Invoice Release</span>
            </a>
          </li>
          <li>
            <a href="{{ route('invoice_payment.index') }}">
            <i class="bi bi-circle"></i><span>Invoice Payment</span>
            </a>
          </li>
          <li>
            <a href="{{ route('project_inv_rel.index') }}">
            <i class="bi bi-circle"></i><span>Project Invoice Release</span>
            </a>
          </li>
          <li>
            <a href="{{ route('retail_inv_rel.index') }}">
            <i class="bi bi-circle"></i><span>Retail Invoice Release</span>
            </a>
          </li>
          <li>
            <a href="{{ route('writeoff_ar.index') }}">
            <i class="bi bi-circle"></i><span>Write Off A/R</span>
            </a>
          </li>
          <li class="nav-item">
              <a class="nav-link collapsed" data-bs-target="#subFna-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-file-earmark-text"></i><span>Reports</span>
                <i class="bi bi-chevron-down ms-auto"></i>
              </a>
              <ul id="subFna-nav" class="nav-content collapse" data-bs-parent="#fna-nav">
                <li>
                  <a href="{{ route('aging_ar_by_invoice.create') }}">
                  <i class="bi bi-circle"></i><span>Aging Ar By Invoice List</span>
                  </a>
                </li>
              </ul>
              <ul id="subFna-nav" class="nav-content collapse" data-bs-parent="#fna-nav">
                <li>
                  <a href="{{ route('ar_woff_list.create') }}">
                  <i class="bi bi-circle"></i><span>AR Write Off List</span>
                  </a>
                </li>
              </ul>
              <ul id="subFna-nav" class="nav-content collapse" data-bs-parent="#fna-nav">
                <li>
                  <a href="{{ route('buku_penjualan.create') }}">
                  <i class="bi bi-circle"></i><span>Buku Penjualan</span>
                  </a>
                </li>
              </ul>
              <ul id="subFna-nav" class="nav-content collapse" data-bs-parent="#fna-nav">
                <li>
                  <a href="{{ route('cust_trans_history.create') }}">
                  <i class="bi bi-circle"></i><span>Customer Transaction History</span>
                  </a>
                </li>
              </ul>
              <ul id="subFna-nav" class="nav-content collapse" data-bs-parent="#fna-nav">
                <li>
                  <a href="{{ route('payment_list.create') }}">
                  <i class="bi bi-circle"></i><span>Payment List</span>
                  </a>
                </li>
              </ul>
          </li>
        </ul>
      </li>
      
      
      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#logistic-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-truck"></i><span>Logistic</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="logistic-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{ route('bbk.index') }}">
              <i class="bi bi-circle"></i><span>BBK (Bon Barang Keluar)</span>
            </a>
          </li>
          <li>
            <a href="{{ route('bbm.index') }}">
              <i class="bi bi-circle"></i><span>BBM (Bon Barang Masuk)</span>
            </a>
          </li>
          <li>
            <a href="{{ route('bpb.index') }}">
              <i class="bi bi-circle"></i><span>BPB (Bon Permintaan Barang)</span>
            </a>
          </li>
          <li>
            <a href="{{ route('do.index') }}">
              <i class="bi bi-circle"></i><span>DO (Delivery Order)</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#subLogistic-nav" data-bs-toggle="collapse" href="#">
              <i class="bi bi-file-earmark-text"></i><span>Reports</span>
              <i class="bi bi-chevron-down ms-auto"></i>
            </a>

            <ul id="subLogistic-nav" class="nav-content collapse" data-bs-parent="#logistic-nav">
              <li>
                <a href="{{ route('sms.create') }}">
                <i class="bi bi-circle"></i><span>Stock Movement Summary</span>
                </a>
              </li>

              <li>
                <a href="{{ route('ss.index') }}">
                  <i class="bi bi-circle"></i><span>Stock Status</span>
                </a>
              </li>

              <li>
                <a href="{{ route('sc.create') }}">
                <i class="bi bi-circle"></i><span>Stock Card</span>
                </a>
              </li>

              <li>
                <a href="{{ route('osr.create') }}">
                <i class="bi bi-circle"></i><span>Outstanding Stock Requisition</span>
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#marketing-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-shop"></i><span>Marketing</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="marketing-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{ route('oc.index') }}">
            <i class="bi bi-circle"></i><span>OC Retail (SA)</span>
            </a>
          </li>
          <li>
            <a href="{{ route('oc_sb.index') }}">
            <i class="bi bi-circle"></i><span>OC Project (SB)</span>
            </a>
          </li>
          <li class="nav-item">
              <a class="nav-link collapsed" data-bs-target="#subMarketing-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-file-earmark-text"></i><span>Reports</span>
                <i class="bi bi-chevron-down ms-auto"></i>
              </a>
              <ul id="subMarketing-nav" class="nav-content collapse" data-bs-parent="#marketing-nav">
                <li>
                  <a href="{{ route('mkt.createMkt') }}">
                  <i class="bi bi-circle"></i><span>Sales Report/Sales Rep</span>
                  </a>
                </li>
              </ul>
              <ul id="subMarketing-nav" class="nav-content collapse" data-bs-parent="#marketing-nav">
                <li>
                  <a href="{{ route('mkt.createMktSs') }}">
                  <i class="bi bi-circle"></i><span>Sales Report/Product Group</span>
                  </a>
                </li>
              </ul>
          </li>
        </ul>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#production-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-building-gear"></i><span>Production</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="production-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{ route('wo.index') }}">
            <i class="bi bi-circle"></i><span>Work Order</span>
            </a>
          </li>
        </ul>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#purchasing-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-cart"></i><span>Purchasing</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="purchasing-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{ route('blawb.index') }}">
            <i class="bi bi-circle"></i><span>BL / AWB</span>
            </a>
          </li>
          <li>
            <a href="{{ route('invoice.index') }}">
            <i class="bi bi-circle"></i><span>Invoice</span>
            </a>
          </li>
          <li>
            <a href="{{ route('tpo.index') }}">
            <i class="bi bi-circle"></i><span>PO</span>
            </a>
          </li>
        </ul>
      </li>

      
      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#teknik-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-tools"></i><span>Teknik</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="teknik-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{ route('delivery_note.index') }}">
            <i class="bi bi-circle"></i><span>Delivery Note (DN)</span>
            </a>
          </li>
          <li>
            <a href="{{ route('maintenance_contract.index') }}">
            <i class="bi bi-circle"></i><span>Mantenance Contract (MC)</span>
            </a>
          </li>
          <li>
            <a href="{{ route('service_invoice_release.index') }}">
            <i class="bi bi-circle"></i><span>Sevice Invoice Release (SD)</span>
            </a>
          </li>
          <li>
            <a href="{{ route('mc_invoice_release.index') }}">
            <i class="bi bi-circle"></i><span>Maintenance Contract Invoice Release (SD)</span>
            </a>
          </li>
          {{-- <li class="nav-item">
              <a class="nav-link collapsed" data-bs-target="#subTeknik-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-file-earmark-text"></i><span>Reports</span>
                <i class="bi bi-chevron-down ms-auto"></i>
              </a>
              <ul id="subTeknik-nav" class="nav-content collapse" data-bs-parent="#teknik-nav">
                <li>
                  <a href="{{ route('mkt.createMkt') }}">
                  <i class="bi bi-circle"></i><span>Sales Report/Sales Rep</span>
                  </a>
                </li>
              </ul>
              <ul id="subTeknik-nav" class="nav-content collapse" data-bs-parent="#teknik-nav">
                <li>
                  <a href="{{ route('mkt.createMktSs') }}">
                  <i class="bi bi-circle"></i><span>Sales Report/Product Group</span>
                  </a>
                </li>
              </ul>
          </li> --}}
        </ul>
      </li>

      {{-- <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-layout-text-window-reverse"></i><span>Tables</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="tables-general.html">
              <i class="bi bi-circle"></i><span>General Tables</span>
            </a>
          </li>
          <li>
            <a href="tables-data.html">
              <i class="bi bi-circle"></i><span>Data Tables</span>
            </a>
          </li>
        </ul>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#charts-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-bar-chart"></i><span>Charts</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="charts-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="#">
              <i class="bi bi-circle"></i><span>Chart.js</span>
            </a>
          </li>
          <li>
            <a href="charts-apexcharts.html">
              <i class="bi bi-circle"></i><span>ApexCharts</span>
            </a>
          </li>
          <li>
            <a href="charts-echarts.html">
              <i class="bi bi-circle"></i><span>ECharts</span>
            </a>
          </li>
        </ul>
      </li> --}}

    </ul>

  </aside>
