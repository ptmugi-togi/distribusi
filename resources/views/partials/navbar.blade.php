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
            <?php if(auth()->user()->level=="IT"){ ?>
            <li class="{{ request()->is('register') ? 'active' : '' }}">
                <a href="/register">
                <i class="bi bi-circle"></i><span>REGISTER</span>
                </a>
          </li>
            <?php } ?>
            <li>
                <a href="/ssgrup">
                <i class="bi bi-circle"></i><span>SubSubGroup</span>
                </a>
            </li>
            <li>
                <a href="/msgrup">
                <i class="bi bi-circle"></i><span>SubGroup</span>
                </a>
            </li>
            <li>
                <a href="/mpgrup">
                <i class="bi bi-circle"></i><span>Product Group</span>
                </a>
            </li>
            <li>
                <a href="/mbrand">
                <i class="bi bi-circle"></i><span>Brand</span>
                </a>
            </li>
            <li>
                <a href="/mitype">
                <i class="bi bi-circle"></i><span>Inventory Type</span>
                </a>
            </li>
            <li>
                <a href="/mcls">
                <i class="bi bi-circle"></i><span>Class</span>
                </a>
            </li>
            <li>
                <a href="/mpromas">
                <i class="bi bi-circle"></i><span>Product</span>
                </a>
            </li>
            <li>
                <a href="/msreno">
                <i class="bi bi-circle"></i><span>Sales Rep</span>
                </a>
            </li>
            <li>
                <a href="/mcindu">
                <i class="bi bi-circle"></i><span>Customer Industry</span>
                </a>
            </li>
            <li>
                <a href="/mstmas">
                <i class="bi bi-circle"></i><span>Shipment</span>
                </a>
            </li>
            <li>
                <a href="/mbranch">
                <i class="bi bi-circle"></i><span>Branch</span>
                </a>
            </li>
            <li>
                <a href="/mdepo">
                <i class="bi bi-circle"></i><span>Depo</span>
                </a>
            </li>
            <li>
                <a href="/cusmas">
                <i class="bi bi-circle"></i><span>Customer Master</span>
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
            <a href="{{ route('ta.index') }}">
              <i class="bi bi-circle"></i><span>TA (Transfer Note)</span>
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
